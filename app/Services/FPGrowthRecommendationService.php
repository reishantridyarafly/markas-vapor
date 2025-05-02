<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class FPGrowthRecommendationService
{
   protected $minSupport;        // Minimal support untuk frequent itemset (dalam persen)
   protected $minConfidence;     // Minimal confidence untuk aturan asosiasi (dalam persen)
   protected $transactions;      // Data transaksi
   protected $frequentItems;     // Item yang sering dibeli
   protected $fpTree;            // FP-Tree
   protected $headerTable;       // Header table untuk FP-Tree

   public function __construct($minSupport = 1, $minConfidence = 50)
   {
      $this->minSupport = $minSupport / 100;  // Konversi ke desimal
      $this->minConfidence = $minConfidence / 100;  // Konversi ke desimal
      $this->frequentItems = [];
      $this->fpTree = ['name' => 'null', 'count' => 0, 'children' => [], 'parent' => null];
      $this->headerTable = [];
      $this->transactions = [];
   }

   /**
    * Memuat data transaksi dari database
    * 
    * @param int $limit Batasan jumlah transaksi yang diproses (opsional)
    * @param string $dateRange Rentang tanggal transaksi (opsional, format: Y-m-d)
    * @return array Data transaksi
    */
   public function loadTransactions($limit = null, $dateRange = null)
   {
      $query = Transaction::where('status', 'completed')
         ->where('type_transaction', 'online');

      if ($dateRange) {
         list($startDate, $endDate) = explode('|', $dateRange);
         $query->whereBetween('transaction_date', [$startDate, $endDate]);
      }

      $transactions = $query->when($limit, function ($query) use ($limit) {
         return $query->limit($limit);
      })
         ->get();

      foreach ($transactions as $transaction) {
         $items = [];
         $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)
            ->with('product')
            ->get();

         foreach ($transactionDetails as $detail) {
            $items[] = $detail->product_id;
         }

         if (!empty($items)) {
            $this->transactions[] = $items;
         }
      }

      return $this->transactions;
   }

   /**
    * Menemukan item-item yang sering dibeli (frequent items)
    * 
    * @return array Item beserta frekuensinya
    */
   public function findFrequentItems()
   {
      $allItems = [];
      $totalTransactions = count($this->transactions);

      // Hitung frekuensi setiap item
      foreach ($this->transactions as $transaction) {
         foreach ($transaction as $item) {
            if (!isset($allItems[$item])) {
               $allItems[$item] = 0;
            }
            $allItems[$item]++;
         }
      }

      // Filter item berdasarkan minimum support
      $minCount = ceil($this->minSupport * $totalTransactions);
      foreach ($allItems as $item => $count) {
         if ($count >= $minCount) {
            $this->frequentItems[$item] = $count;
         }
      }

      // Urutkan berdasarkan frekuensi (descending)
      arsort($this->frequentItems);

      return $this->frequentItems;
   }

   /**
    * Membangun FP-Tree dari data transaksi
    */
   public function buildFPTree()
   {
      // Inisialisasi header table
      foreach ($this->frequentItems as $item => $count) {
         $this->headerTable[$item] = ['count' => $count, 'nodes' => []];
      }

      // Bangun FP-Tree untuk setiap transaksi
      foreach ($this->transactions as $transaction) {
         $orderedItems = [];

         // Filter item yang frequent dan urutkan berdasarkan frekuensi
         foreach ($this->frequentItems as $item => $count) {
            if (in_array($item, $transaction)) {
               $orderedItems[] = $item;
            }
         }

         if (!empty($orderedItems)) {
            $this->insertTree($orderedItems, $this->fpTree);
         }
      }
   }

   /**
    * Menyisipkan path ke dalam FP-Tree
    * 
    * @param array $items Item yang akan disisipkan
    * @param array &$tree Referensi ke FP-Tree
    * @param int $count Jumlah kemunculan (default: 1)
    */
   protected function insertTree($items, &$tree, $count = 1)
   {
      if (empty($items)) {
         return;
      }

      $item = array_shift($items);

      // Cek apakah item sudah ada di children node saat ini
      $found = false;
      foreach ($tree['children'] as &$child) {
         if ($child['name'] === $item) {
            $child['count'] += $count;
            $this->insertTree($items, $child, $count);

            $found = true;
            break;
         }
      }

      // Jika tidak ditemukan, buat node baru
      if (!$found) {
         $newNode = [
            'name' => $item,
            'count' => $count,
            'children' => [],
            'parent' => &$tree
         ];

         $tree['children'][] = $newNode;

         // Update header table
         $this->headerTable[$item]['nodes'][] = &$tree['children'][count($tree['children']) - 1];

         // Rekursi untuk sisa items
         if (!empty($items)) {
            $this->insertTree($items, $tree['children'][count($tree['children']) - 1], $count);
         }
      }
   }

   /**
    * Mine FP-Tree untuk menghasilkan frequent patterns
    * 
    * @return array Pola-pola yang sering muncul
    */
   public function mineFrequentPatterns()
   {
      $patterns = [];

      // Proses untuk setiap item dalam header table (dari frekuensi terendah)
      $items = array_keys($this->frequentItems);
      for ($i = count($items) - 1; $i >= 0; $i--) {
         $item = $items[$i];

         // Conditional pattern base untuk item ini
         $conditionalBase = $this->findConditionalPatternBase($item);

         if (!empty($conditionalBase)) {
            // Tambahkan item sendiri sebagai pattern
            if (!isset($patterns[$item])) {
               $patterns[$item] = $this->frequentItems[$item];
            }

            // Buat conditional FP-Tree
            $conditionalTree = $this->buildConditionalFPTree($conditionalBase);

            if (!empty($conditionalTree['frequentItems'])) {
               // Rekursi untuk mining conditional tree
               $subPatterns = $this->mineConditionalTree($conditionalTree['tree'], $conditionalTree['frequentItems'], [$item]);

               // Gabungkan dengan hasil patterns
               foreach ($subPatterns as $pattern => $count) {
                  $patterns[$pattern] = $count;
               }
            }
         }
      }

      return $patterns;
   }

   /**
    * Menemukan conditional pattern base untuk suatu item
    * 
    * @param string $item Item yang dicari
    * @return array Conditional pattern base
    */
   protected function findConditionalPatternBase($item)
   {
      $conditionalBase = [];

      // Dapatkan semua node untuk item ini dari header table
      if (isset($this->headerTable[$item]['nodes'])) {
         foreach ($this->headerTable[$item]['nodes'] as $node) {
            $prefix = [];
            $count = $node['count'];

            // Telusuri ke atas sampai root
            $current = $node;
            while ($current['parent']['name'] !== 'null') {
               $prefix[] = $current['parent']['name'];
               $current = $current['parent'];
            }

            if (!empty($prefix)) {
               $conditionalBase[] = [
                  'items' => array_reverse($prefix),
                  'count' => $count
               ];
            }
         }
      }

      return $conditionalBase;
   }

   /**
    * Membangun conditional FP-Tree
    * 
    * @param array $conditionalBase Conditional pattern base
    * @return array FP-Tree dan frequent items
    */
   protected function buildConditionalFPTree($conditionalBase)
   {
      $items = [];
      $totalCount = 0;

      // Hitung frekuensi setiap item dalam conditional base
      foreach ($conditionalBase as $pattern) {
         $totalCount += $pattern['count'];
         foreach ($pattern['items'] as $item) {
            if (!isset($items[$item])) {
               $items[$item] = 0;
            }
            $items[$item] += $pattern['count'];
         }
      }

      // Filter item berdasarkan minimum support
      $minCount = ceil($this->minSupport * count($this->transactions));
      $frequentItems = [];
      foreach ($items as $item => $count) {
         if ($count >= $minCount) {
            $frequentItems[$item] = $count;
         }
      }

      // Urutkan berdasarkan frekuensi global
      $orderedFrequentItems = [];
      foreach ($this->frequentItems as $item => $count) {
         if (isset($frequentItems[$item])) {
            $orderedFrequentItems[$item] = $frequentItems[$item];
         }
      }

      // Bangun tree
      $tree = ['name' => 'null', 'count' => 0, 'children' => [], 'parent' => null];

      // Insert setiap conditional pattern ke tree
      foreach ($conditionalBase as $pattern) {
         $orderedItems = [];

         // Filter & urutkan items
         foreach ($orderedFrequentItems as $item => $count) {
            if (in_array($item, $pattern['items'])) {
               $orderedItems[] = $item;
            }
         }

         if (!empty($orderedItems)) {
            $this->insertConditionalTree($orderedItems, $tree, $pattern['count']);
         }
      }

      return [
         'tree' => $tree,
         'frequentItems' => $orderedFrequentItems
      ];
   }

   /**
    * Menyisipkan path ke conditional tree
    * 
    * @param array $items Item yang akan disisipkan
    * @param array &$tree Referensi ke conditional tree
    * @param int $count Jumlah kemunculan
    */
   protected function insertConditionalTree($items, &$tree, $count)
   {
      if (empty($items)) {
         return;
      }

      $item = array_shift($items);

      // Cek apakah item sudah ada di children node saat ini
      $found = false;
      foreach ($tree['children'] as &$child) {
         if ($child['name'] === $item) {
            $child['count'] += $count;
            $this->insertConditionalTree($items, $child, $count);

            $found = true;
            break;
         }
      }

      // Jika tidak ditemukan, buat node baru
      if (!$found) {
         $newNode = [
            'name' => $item,
            'count' => $count,
            'children' => [],
            'parent' => &$tree
         ];

         $tree['children'][] = $newNode;

         // Rekursi untuk sisa items
         if (!empty($items)) {
            $this->insertConditionalTree($items, $tree['children'][count($tree['children']) - 1], $count);
         }
      }
   }

   /**
    * Mine conditional FP-Tree untuk menghasilkan patterns
    * 
    * @param array $tree Conditional tree
    * @param array $frequentItems Frequent items dalam conditional tree
    * @param array $suffix Suffix pattern
    * @return array Patterns yang ditemukan
    */
   protected function mineConditionalTree($tree, $frequentItems, $suffix)
   {
      $patterns = [];

      // Untuk setiap frequent item, buat pattern dengan suffix
      foreach ($frequentItems as $item => $count) {
         $newSuffix = array_merge([$item], $suffix);
         $patternKey = implode(',', $newSuffix);
         $patterns[$patternKey] = $count;

         // Dapatkan conditional pattern base untuk item ini
         $conditionalBase = $this->findNodePathsInTree($item, $tree);

         if (!empty($conditionalBase)) {
            // Buat conditional FP-Tree baru
            $conditionalTree = $this->buildConditionalFPTree($conditionalBase);

            if (!empty($conditionalTree['frequentItems'])) {
               // Rekursi untuk mining conditional tree
               $subPatterns = $this->mineConditionalTree(
                  $conditionalTree['tree'],
                  $conditionalTree['frequentItems'],
                  $newSuffix
               );

               // Gabungkan dengan hasil patterns
               foreach ($subPatterns as $pattern => $subCount) {
                  $patterns[$pattern] = $subCount;
               }
            }
         }
      }

      return $patterns;
   }

   /**
    * Menemukan path node dalam tree
    * 
    * @param string $item Item yang dicari
    * @param array $tree Tree yang ditelusuri
    * @return array Path yang ditemukan
    */
   protected function findNodePathsInTree($item, $tree)
   {
      $paths = [];

      // Cari node dengan nama yang sesuai
      $this->findNodeRecursively($item, $tree, $paths);

      return $paths;
   }

   /**
    * Mencari node secara rekursif
    * 
    * @param string $item Item yang dicari
    * @param array $node Node saat ini
    * @param array &$paths Hasil path yang ditemukan
    */
   protected function findNodeRecursively($item, $node, &$paths)
   {
      // Cek setiap child node
      foreach ($node['children'] as $child) {
         if ($child['name'] === $item) {
            // Node ditemukan, telusuri ke atas untuk mendapatkan path
            $prefix = [];
            $count = $child['count'];

            $current = $child;
            while ($current['parent']['name'] !== 'null') {
               $prefix[] = $current['parent']['name'];
               $current = $current['parent'];
            }

            if (!empty($prefix)) {
               $paths[] = [
                  'items' => array_reverse($prefix),
                  'count' => $count
               ];
            }
         }

         // Rekursi ke child nodes
         $this->findNodeRecursively($item, $child, $paths);
      }
   }

   /**
    * Menghasilkan aturan asosiasi dari frequent patterns
    * 
    * @param array $patterns Frequent patterns
    * @return array Aturan asosiasi
    */
   public function generateAssociationRules($patterns)
   {
      $rules = [];
      $totalTransactions = count($this->transactions);

      foreach ($patterns as $pattern => $count) {
         $items = explode(',', $pattern);

         // Hanya proses pattern dengan lebih dari 1 item
         if (count($items) > 1) {
            // Buat semua kemungkinan subset
            $subsets = $this->getAllSubsets($items);

            foreach ($subsets as $subset) {
               // Hitung confidence: support(X∪Y) / support(X)
               $antecedent = implode(',', $subset);
               $consequent = implode(',', array_diff($items, $subset));

               // Skip jika antecedent atau consequent kosong
               if (empty($antecedent) || empty($consequent)) {
                  continue;
               }

               // Cari support untuk antecedent
               $supportAntecedent = 0;
               if (isset($patterns[$antecedent])) {
                  $supportAntecedent = $patterns[$antecedent];
               } else {
                  // Hitung support untuk antecedent jika tidak ada di patterns
                  $antecedentItems = explode(',', $antecedent);
                  $countAntecedent = 0;

                  foreach ($this->transactions as $transaction) {
                     $match = true;
                     foreach ($antecedentItems as $item) {
                        if (!in_array($item, $transaction)) {
                           $match = false;
                           break;
                        }
                     }

                     if ($match) {
                        $countAntecedent++;
                     }
                  }

                  $supportAntecedent = $countAntecedent;
               }

               if ($supportAntecedent > 0) {
                  $confidence = $count / $supportAntecedent;

                  // Filter berdasarkan minimum confidence
                  if ($confidence >= $this->minConfidence) {
                     $support = $count / $totalTransactions;

                     // Tambahkan rule ke hasil
                     $rules[] = [
                        'antecedent' => explode(',', $antecedent),
                        'consequent' => explode(',', $consequent),
                        'support' => $support,
                        'confidence' => $confidence
                     ];
                  }
               }
            }
         }
      }

      // Urutkan berdasarkan confidence (descending)
      usort($rules, function ($a, $b) {
         return $b['confidence'] <=> $a['confidence'];
      });

      return $rules;
   }

   /**
    * Mendapatkan semua subset dari array items
    * 
    * @param array $items Array items
    * @return array Semua subset
    */
   protected function getAllSubsets($items)
   {
      $count = count($items);
      $members = pow(2, $count);
      $subsets = [];

      for ($i = 1; $i < $members - 1; $i++) {
         $subset = [];

         for ($j = 0; $j < $count; $j++) {
            if ($i & (1 << $j)) {
               $subset[] = $items[$j];
            }
         }

         $subsets[] = $subset;
      }

      return $subsets;
   }

   /**
    * Menghasilkan rekomendasi produk untuk pelanggan berdasarkan riwayat transaksi
    * 
    * @param array $productIds Array ID produk yang pernah dibeli
    * @param int $limit Batasan jumlah rekomendasi
    * @return array Produk yang direkomendasikan
    */
   public function getProductRecommendations($productIds, $limit = 5)
   {
      // Eksekusi proses FP-Growth
      $this->loadTransactions();
      $this->findFrequentItems();
      $this->buildFPTree();
      $patterns = $this->mineFrequentPatterns();
      $rules = $this->generateAssociationRules($patterns);

      // Kumpulkan semua produk yang direkomendasikan
      $recommendedProducts = [];
      $addedProducts = [];

      foreach ($rules as $rule) {
         // Cek apakah produk yang pernah dibeli ada di antecedent
         $match = false;
         foreach ($productIds as $productId) {
            if (in_array($productId, $rule['antecedent'])) {
               $match = true;
               break;
            }
         }

         // Jika cocok, tambahkan consequent ke rekomendasi
         if ($match) {
            foreach ($rule['consequent'] as $productId) {
               // Skip jika produk sudah dibeli atau sudah ditambahkan
               if (in_array($productId, $productIds) || in_array($productId, $addedProducts)) {
                  continue;
               }

               $product = Product::find($productId);
               if ($product && $product->status == 0 && $product->stock > 0) {
                  $recommendedProducts[] = [
                     'product' => $product,
                     'confidence' => $rule['confidence'],
                     'support' => $rule['support']
                  ];

                  $addedProducts[] = $productId;

                  // Hentikan jika sudah mencapai limit
                  if (count($addedProducts) >= $limit) {
                     break 2;
                  }
               }
            }
         }
      }

      return $recommendedProducts;
   }

   /**
    * Menampilkan rekomendasi produk berdasarkan produk yang sedang dilihat
    * 
    * @param string $productId ID produk yang sedang dilihat
    * @param int $limit Batasan jumlah rekomendasi
    * @return array Produk yang direkomendasikan
    */
   public function getRelatedProducts($productId, $limit = 4)
   {
      return $this->getProductRecommendations([$productId], $limit);
   }

   /**
    * Menampilkan rekomendasi produk berdasarkan riwayat pembelian pelanggan
    * 
    * @param array $transactionIds Array ID transaksi pelanggan
    * @param int $limit Batasan jumlah rekomendasi
    * @return array Produk yang direkomendasikan
    */
   public function getPersonalizedRecommendations($transactionIds, $limit = 5)
   {
      // Dapatkan semua produk yang pernah dibeli
      $purchasedProducts = [];

      foreach ($transactionIds as $transactionId) {
         $details = TransactionDetail::where('transaction_id', $transactionId)
            ->get();

         foreach ($details as $detail) {
            $purchasedProducts[] = $detail->product_id;
         }
      }

      return $this->getProductRecommendations(array_unique($purchasedProducts), $limit);
   }
}
