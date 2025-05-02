<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\FPGrowthRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductRecommendationController extends Controller
{
    protected $fpGrowthService;

    public function __construct(FPGrowthRecommendationService $fpGrowthService)
    {
        $this->fpGrowthService = $fpGrowthService;
    }

    /**
     * Menampilkan rekomendasi produk pada halaman detail produk
     * 
     * @param Request $request
     * @param string $productId
     * @return \Illuminate\Http\Response
     */
    public function showRelatedProducts(Request $request, $productId)
    {
        // Dapatkan produk yang sedang dilihat
        $product = Product::findOrFail($productId);

        // Dapatkan rekomendasi produk terkait
        $relatedProducts = $this->fpGrowthService->getRelatedProducts($productId, 4);

        return view('products.related', compact('product', 'relatedProducts'));
    }

    /**
     * Menampilkan rekomendasi produk di halaman akun pelanggan
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    // Di controller
    public function showPersonalizedRecommendations(Request $request)
    {
        $userId = auth()->id();

        // Ambil transaksi user yang completed
        $transactions = Transaction::where('user_id', $userId)
            ->where('status', 'completed')
            ->get();

        $transactionIds = $transactions->pluck('id')->toArray();

        // Buat instance FPGrowth dengan support 0.1% dan confidence 25%
        $fpService = new FPGrowthRecommendationService(0.1, 25);

        // Dapatkan rekomendasi
        $recommendedProducts = $fpService->getPersonalizedRecommendations($transactionIds, 5);

        // Fallback jika tidak ada rekomendasi
        if (empty($recommendedProducts)) {
            $popularProducts = Product::where('status', 0)
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();

            foreach ($popularProducts as $product) {
                $recommendedProducts[] = [
                    'product' => $product,
                    'confidence' => 1.0,
                    'support' => 1.0
                ];
            }
        }

        return view('backend.fpgrowth.account', compact('recommendedProducts'));
    }

    /**
     * Halaman admin untuk melihat hasil analisis FP-Growth
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function adminAnalytics(Request $request)
    {
        // Parameter untuk analisis
        $minSupport = $request->input('min_support', 1); // 1%
        $minConfidence = $request->input('min_confidence', 50); // 50%
        $dateRange = $request->input('date_range', null);
        $limit = $request->input('limit', 1000);

        // Inisialisasi service dengan parameter baru
        $fpGrowthService = new FPGrowthRecommendationService($minSupport, $minConfidence);

        // Eksekusi proses FP-Growth
        $transactions = $fpGrowthService->loadTransactions($limit, $dateRange);
        $frequentItems = $fpGrowthService->findFrequentItems();
        $fpGrowthService->buildFPTree();
        $patterns = $fpGrowthService->mineFrequentPatterns();
        $rules = $fpGrowthService->generateAssociationRules($patterns);

        // Format data untuk tampilan
        $formattedRules = [];
        foreach ($rules as $rule) {
            // Dapatkan informasi produk untuk antecedent
            $antecedentProducts = Product::whereIn('id', $rule['antecedent'])->get();
            $antecedentNames = $antecedentProducts->pluck('name')->toArray();

            // Dapatkan informasi produk untuk consequent
            $consequentProducts = Product::whereIn('id', $rule['consequent'])->get();
            $consequentNames = $consequentProducts->pluck('name')->toArray();

            $formattedRules[] = [
                'antecedent' => implode(', ', $antecedentNames),
                'consequent' => implode(', ', $consequentNames),
                'support' => number_format($rule['support'] * 100, 2) . '%',
                'confidence' => number_format($rule['confidence'] * 100, 2) . '%'
            ];
        }

        return view('backend.fpgrowth.analytics', compact(
            'formattedRules',
            'minSupport',
            'minConfidence',
            'dateRange',
            'limit'
        ));
    }
}
