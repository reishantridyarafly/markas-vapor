<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class VapeStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('transaction_details')->truncate();
        DB::table('transactions')->truncate();
        DB::table('ratings')->truncate();
        DB::table('products')->truncate();
        DB::table('catalog')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Catalogs
        $catalogs = $this->createCatalogs();

        // 2. Create Products
        $products = $this->createProducts($catalogs);

        // 3. Create Transactions with realistic patterns
        $this->createTransactions($products);

        // 4. Create Ratings
        $this->createRatings($products);

        $this->command->info('✅ Vape Store seeder completed successfully!');
        $this->command->info('📊 Created: ' . count($products) . ' products');
        $this->command->info('🛒 Created: ~200 transactions');
        $this->command->info('⭐ Created: ~150 ratings');
    }

    /**
     * Create product catalogs
     */
    private function createCatalogs(): array
    {
        $catalogs = [
            ['name' => 'Vape Devices', 'slug' => 'vape-devices'],
            ['name' => 'Pod Systems', 'slug' => 'pod-systems'],
            ['name' => 'Freebase Liquid', 'slug' => 'freebase-liquid'],
            ['name' => 'Salt Nic Liquid', 'slug' => 'salt-nic-liquid'],
            ['name' => 'Coils', 'slug' => 'coils'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Batteries', 'slug' => 'batteries'],
        ];

        $catalogIds = [];
        foreach ($catalogs as $catalog) {
            $id = Str::uuid()->toString();
            DB::table('catalog')->insert([
                'id' => $id,
                'name' => $catalog['name'],
                'slug' => $catalog['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $catalogIds[$catalog['slug']] = $id;
        }

        return $catalogIds;
    }

    /**
     * Create products
     */
    private function createProducts(array $catalogs): array
    {
        $products = [];

        // Vape Devices (5 products)
        $vapeDevices = [
            ['name' => 'SMOK Nord 4 80W Pod Kit', 'price' => 450000, 'weight' => 150],
            ['name' => 'Vaporesso XROS 3 Pod Kit', 'price' => 380000, 'weight' => 120],
            ['name' => 'GeekVape Aegis Legend 2', 'price' => 850000, 'weight' => 250],
            ['name' => 'Voopoo Drag X Plus', 'price' => 650000, 'weight' => 200],
            ['name' => 'Uwell Caliburn A2', 'price' => 320000, 'weight' => 100],
        ];

        foreach ($vapeDevices as $device) {
            $products[] = $this->insertProduct(
                $device['name'],
                $catalogs['vape-devices'],
                $device['price'],
                $device['weight'],
                50,
                'High-quality vape device with advanced features and long battery life.'
            );
        }

        // Pod Systems (4 products)
        $podSystems = [
            ['name' => 'Lost Vape Ursa Nano Pod', 'price' => 280000, 'weight' => 80],
            ['name' => 'Vaporesso LUXE Q2 Pod', 'price' => 350000, 'weight' => 90],
            ['name' => 'Voopoo Vinci 3 Pod Kit', 'price' => 420000, 'weight' => 110],
            ['name' => 'Smoant Pasito II Pod', 'price' => 390000, 'weight' => 95],
        ];

        foreach ($podSystems as $pod) {
            $products[] = $this->insertProduct(
                $pod['name'],
                $catalogs['pod-systems'],
                $pod['price'],
                $pod['weight'],
                45,
                'Portable and easy-to-use pod system perfect for beginners and veterans.'
            );
        }

        // Freebase Liquids (10 products)
        $freebaseLiquids = [
            ['name' => 'Liquid Freebase Strawberry Cream 60ml', 'price' => 85000],
            ['name' => 'Liquid Freebase Mango Ice 60ml', 'price' => 85000],
            ['name' => 'Liquid Freebase Grape Mix 60ml', 'price' => 80000],
            ['name' => 'Liquid Freebase Vanilla Custard 60ml', 'price' => 90000],
            ['name' => 'Liquid Freebase Lychee Mint 60ml', 'price' => 85000],
            ['name' => 'Liquid Freebase Blueberry Blast 60ml', 'price' => 85000],
            ['name' => 'Liquid Freebase Watermelon Fresh 60ml', 'price' => 80000],
            ['name' => 'Liquid Freebase Coffee Latte 60ml', 'price' => 95000],
            ['name' => 'Liquid Freebase Double Apple 60ml', 'price' => 85000],
            ['name' => 'Liquid Freebase Mint Breeze 60ml', 'price' => 80000],
        ];

        foreach ($freebaseLiquids as $liquid) {
            $products[] = $this->insertProduct(
                $liquid['name'],
                $catalogs['freebase-liquid'],
                $liquid['price'],
                70,
                100,
                'Premium quality freebase liquid with authentic flavor. Available in 3mg and 6mg nicotine.'
            );
        }

        // Salt Nic Liquids (8 products)
        $saltNicLiquids = [
            ['name' => 'Salt Nic Strawberry Kiwi 30ml', 'price' => 75000],
            ['name' => 'Salt Nic Lychee Ice 30ml', 'price' => 75000],
            ['name' => 'Salt Nic Grape Soda 30ml', 'price' => 70000],
            ['name' => 'Salt Nic Mango Peach 30ml', 'price' => 75000],
            ['name' => 'Salt Nic Mint Tobacco 30ml', 'price' => 70000],
            ['name' => 'Salt Nic Blueberry Lemon 30ml', 'price' => 75000],
            ['name' => 'Salt Nic Watermelon Candy 30ml', 'price' => 70000],
            ['name' => 'Salt Nic Vanilla Milkshake 30ml', 'price' => 80000],
        ];

        foreach ($saltNicLiquids as $salt) {
            $products[] = $this->insertProduct(
                $salt['name'],
                $catalogs['salt-nic-liquid'],
                $salt['price'],
                50,
                120,
                'Smooth salt nicotine liquid perfect for pod systems. Available in 20mg and 30mg.'
            );
        }

        // Coils (6 products)
        $coils = [
            ['name' => 'SMOK Nord 4 RPM Coil 0.4ohm (5pcs)', 'price' => 120000],
            ['name' => 'Vaporesso GTX Coil 0.6ohm (5pcs)', 'price' => 110000],
            ['name' => 'GeekVape B Series Coil 0.4ohm (5pcs)', 'price' => 115000],
            ['name' => 'Voopoo PnP Coil 0.3ohm (5pcs)', 'price' => 105000],
            ['name' => 'Uwell Caliburn G Coil 0.8ohm (4pcs)', 'price' => 95000],
            ['name' => 'Lost Vape UB Pro Coil 0.15ohm (4pcs)', 'price' => 125000],
        ];

        foreach ($coils as $coil) {
            $products[] = $this->insertProduct(
                $coil['name'],
                $catalogs['coils'],
                $coil['price'],
                30,
                80,
                'Replacement coil for optimal flavor and vapor production. Original authentic product.'
            );
        }

        // Accessories (5 products)
        $accessories = [
            ['name' => 'Vape USB-C Charging Cable', 'price' => 35000, 'weight' => 20],
            ['name' => 'Silicone Vape Case Cover', 'price' => 45000, 'weight' => 30],
            ['name' => 'Vape Carrying Pouch', 'price' => 55000, 'weight' => 50],
            ['name' => 'Drip Tip 510 Resin', 'price' => 40000, 'weight' => 10],
            ['name' => 'Cotton Bacon Prime', 'price' => 65000, 'weight' => 25],
        ];

        foreach ($accessories as $accessory) {
            $products[] = $this->insertProduct(
                $accessory['name'],
                $catalogs['accessories'],
                $accessory['price'],
                $accessory['weight'],
                60,
                'Essential vape accessory for better experience and maintenance.'
            );
        }

        // Batteries (3 products)
        $batteries = [
            ['name' => 'Samsung 18650 Battery 3000mAh', 'price' => 85000, 'weight' => 50],
            ['name' => 'Sony VTC6 18650 Battery 3000mAh', 'price' => 95000, 'weight' => 50],
            ['name' => 'LG HG2 18650 Battery 3000mAh', 'price' => 80000, 'weight' => 50],
        ];

        foreach ($batteries as $battery) {
            $products[] = $this->insertProduct(
                $battery['name'],
                $catalogs['batteries'],
                $battery['price'],
                $battery['weight'],
                40,
                'High-drain lithium-ion battery for vape mods. Original and authentic.'
            );
        }

        return $products;
    }

    /**
     * Insert a single product
     */
    private function insertProduct(
        string $name,
        string $catalogId,
        int $price,
        int $weight,
        int $stock,
        string $description
    ): array {
        $id = Str::uuid()->toString();
        $slug = Str::slug($name);
        $beforePrice = $price * 1.15; // 15% discount

        DB::table('products')->insert([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'short_description' => substr($description, 0, 100) . '...',
            'before_price' => $beforePrice,
            'after_price' => $price,
            'stock' => $stock,
            'weight' => $weight,
            'status' => 0,
            'cover_photo' => 'default-product.jpg',
            'catalog_id' => $catalogId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'catalog_id' => $catalogId,
        ];
    }

    /**
     * Create realistic transactions
     */
    private function createTransactions(array $products): void
    {
        // Get user IDs (assuming users exist)
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('⚠️ No users found. Creating dummy user IDs for transactions.');
            $userIds = [
                Str::uuid()->toString(),
                Str::uuid()->toString(),
                Str::uuid()->toString(),
            ];
        }

        // Pattern 1: Vape Device + Freebase Liquid (Most common - 60 transactions)
        $vapeDevices = array_filter($products, function ($p) {
            return Str::contains($p['name'], ['SMOK', 'Vaporesso XROS', 'GeekVape', 'Voopoo', 'Uwell']);
        });

        $freebaseLiquids = array_filter($products, function ($p) {
            return Str::contains($p['name'], 'Freebase');
        });

        for ($i = 0; $i < 60; $i++) {
            $device = $vapeDevices[array_rand($vapeDevices)];
            $liquid = $freebaseLiquids[array_rand($freebaseLiquids)];
            $this->createSingleTransaction($userIds, [$device, $liquid], 'Device + Freebase');
        }

        // Pattern 2: Pod System + Salt Nic (50 transactions)
        $podSystems = array_filter($products, function ($p) {
            return Str::contains($p['name'], ['Pod', 'Caliburn', 'Ursa', 'LUXE']);
        });

        $saltNicLiquids = array_filter($products, function ($p) {
            return Str::contains($p['name'], 'Salt Nic');
        });

        for ($i = 0; $i < 50; $i++) {
            $pod = $podSystems[array_rand($podSystems)];
            $salt = $saltNicLiquids[array_rand($saltNicLiquids)];
            $this->createSingleTransaction($userIds, [$pod, $salt], 'Pod + Salt Nic');
        }

        // Pattern 3: Device + Liquid + Coil (40 transactions)
        $coils = array_filter($products, function ($p) {
            return Str::contains($p['name'], 'Coil');
        });

        for ($i = 0; $i < 40; $i++) {
            $device = $vapeDevices[array_rand($vapeDevices)];
            $liquid = $freebaseLiquids[array_rand($freebaseLiquids)];
            $coil = $coils[array_rand($coils)];
            $this->createSingleTransaction($userIds, [$device, $liquid, $coil], 'Device + Liquid + Coil');
        }

        // Pattern 4: Multiple Liquids Only (30 transactions)
        for ($i = 0; $i < 30; $i++) {
            $liquid1 = $freebaseLiquids[array_rand($freebaseLiquids)];
            $liquid2 = $freebaseLiquids[array_rand($freebaseLiquids)];
            $this->createSingleTransaction($userIds, [$liquid1, $liquid2], 'Multiple Liquids');
        }

        // Pattern 5: Device + Accessories (20 transactions)
        $accessories = array_filter($products, function ($p) {
            return Str::contains($p['name'], ['USB', 'Case', 'Pouch', 'Drip', 'Cotton']);
        });

        for ($i = 0; $i < 20; $i++) {
            $device = $vapeDevices[array_rand($vapeDevices)];
            $accessory = $accessories[array_rand($accessories)];
            $this->createSingleTransaction($userIds, [$device, $accessory], 'Device + Accessory');
        }

        $this->command->info('✅ Created 200 realistic transactions');
    }

    /**
     * Create a single transaction
     */
    private function createSingleTransaction(array $userIds, array $items, string $pattern): void
    {
        $transactionId = Str::uuid()->toString();
        $userId = $userIds[array_rand($userIds)];

        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item['price'];
        }

        $shippingCost = rand(15000, 35000);
        $totalPrice += $shippingCost;

        // Random date in last 3 months
        $daysAgo = rand(1, 90);
        $transactionDate = Carbon::now()->subDays($daysAgo);

        DB::table('transactions')->insert([
            'id' => $transactionId,
            'code' => 'TRX-' . strtoupper(Str::random(10)),
            'transaction_date' => $transactionDate,
            'customer_name' => 'Customer ' . substr($userId, 0, 8),
            'address_id' => null,
            'note' => 'Pattern: ' . $pattern,
            'shipping_cost' => $shippingCost,
            'status' => 'completed',
            'type_transaction' => 'online',
            'type_payment' => rand(0, 1) ? 'Bank Transfer' : 'E-Wallet',
            'transfer_proof' => null,
            'courier' => 'JNE',
            'resi' => 'JNE' . rand(100000000, 999999999),
            'discount' => 0,
            'total_price' => $totalPrice,
            'created_at' => $transactionDate,
            'updated_at' => $transactionDate,
        ]);

        // Insert transaction details
        foreach ($items as $item) {
            $quantity = rand(1, 3);
            $itemTotal = $item['price'] * $quantity;

            DB::table('transaction_details')->insert([
                'id' => Str::uuid()->toString(),
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'quantity' => $quantity,
                'unit_price' => $item['price'],
                'total_price' => $itemTotal,
                'created_at' => $transactionDate,
                'updated_at' => $transactionDate,
            ]);
        }
    }

    /**
     * Create ratings for products
     */
    private function createRatings(array $products): void
    {
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $userIds = [
                Str::uuid()->toString(),
                Str::uuid()->toString(),
                Str::uuid()->toString(),
            ];
        }

        $comments = [
            'Produk sangat bagus, sesuai ekspektasi!',
            'Kualitas mantap, pengiriman cepat.',
            'Sangat puas dengan pembelian ini.',
            'Original dan berkualitas tinggi.',
            'Recommended seller, produk original!',
            'Harga sebanding dengan kualitas.',
            'Packaging rapi, produk aman sampai tujuan.',
            'Sudah repeat order berkali-kali.',
            'Terbaik di kelasnya!',
            'Worth it banget, recommend!',
        ];

        foreach ($products as $product) {
            // Each product gets 3-8 ratings
            $ratingCount = rand(3, 8);

            for ($i = 0; $i < $ratingCount; $i++) {
                $rating = rand(35, 50) / 10; // 3.5 to 5.0
                $userId = $userIds[array_rand($userIds)];
                $comment = $comments[array_rand($comments)];
                $daysAgo = rand(1, 60);

                DB::table('ratings')->insert([
                    'id' => Str::uuid()->toString(),
                    'user_id' => $userId,
                    'product_id' => $product['id'],
                    'rating' => $rating,
                    'comment' => $comment,
                    'created_at' => Carbon::now()->subDays($daysAgo),
                    'updated_at' => Carbon::now()->subDays($daysAgo),
                ]);
            }
        }

        $this->command->info('✅ Created ratings for all products');
    }
}
