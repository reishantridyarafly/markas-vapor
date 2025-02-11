<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Province::all();

        foreach ($provinces as $province) {
            $cities = Http::withOptions(['verify' => false,])->withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get('https://api.rajaongkir.com/starter/city?province=' . $province->id)->json()['rajaongkir']['results'];

            $insert_city = [];

            foreach ($cities as $city) {

                $data = [
                    'province_id'   => $province->id,
                    'type'          => $city['type'],
                    'name'          => $city['type'] . ' ' . $city['city_name'],
                    'postal_code'   => $city['postal_code'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                $insert_city[] = $data;
            }

            $insert_city = collect($insert_city);

            $city_chunks = $insert_city->chunk(100);

            foreach ($city_chunks as $chunk) {
                City::insert($chunk->toArray());
            }
        }
    }
}
