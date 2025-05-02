<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Create 10 transactions
        for ($i = 0; $i < 100; $i++) {
            // Create transaction
            // Get a random customer user first to ensure consistency
            $customer = User::where('type', 2)->inRandomOrder()->first();

            if (!$customer) {
                throw new \Exception('No customer users found in database');
            }

            $transaction = Transaction::create([
                'id' => (string) Str::uuid(),
                'code' => 'T' . Carbon::now()->subDays(rand(1, 30))->format('Ymd') . str_pad(($i + 1), 4, '0', STR_PAD_LEFT),
                'transaction_date' => Carbon::now()->subDays(rand(1, 30)),
                'customer_name' => $customer->first_name . ' ' . $customer->last_name,
                'user_id' => $customer->id,
                'status' => 'completed',
                'type_transaction' => $faker->randomElement(['online', 'offline']),
                'type_payment' => $faker->randomElement(['cash', 'transfer']),
                'shipping_cost' => rand(10000, 50000),
                'courier' => $faker->randomElement(['JNE', 'J&T', 'SiCepat', 'AnterAja', 'Pos Indonesia']),
                'resi' => strtoupper(Str::random(12)),
                'discount' => rand(0, 50000),
                'total_price' => rand(100000, 1000000)
            ]);

            // Create 1-3 transaction details for each transaction
            $detailCount = rand(1, 3);
            for ($j = 0; $j < $detailCount; $j++) {
                TransactionDetail::create([
                    'id' => (string) Str::uuid(),
                    'transaction_id' => $transaction->id,
                    'product_id' => Product::inRandomOrder()->first()->id,
                    'quantity' => rand(1, 5),
                    'unit_price' => rand(50000, 200000),
                    'total_price' => rand(50000, 1000000)
                ]);
            }
        }
    }
}
