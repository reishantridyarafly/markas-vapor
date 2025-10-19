<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;

class Address extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'address';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getProvinceNameAttribute()
    {
        return Cache::remember("province_{$this->province_id}", 3600, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'key' => env('RAJAONGKIR_API_KEY'),
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            if ($response->successful()) {
                $provinces = $response->json()['data'] ?? [];
                $province = collect($provinces)->firstWhere('id', $this->province_id);
                return $province['name'] ?? '-';
            }
            return '-';
        });
    }

    public function getDistrictNameAttribute()
    {
        return Cache::remember("district_{$this->district_id}", 3600, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'key' => env('RAJAONGKIR_API_KEY'),
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$this->province_id}");

            if ($response->successful()) {
                $cities = $response->json()['data'] ?? [];
                $city = collect($cities)->firstWhere('id', $this->district_id);
                return $city['name'] ?? '-';
            }
            return '-';
        });
    }

    public function getSubdistrictNameAttribute()
    {
        return Cache::remember("subdistrict_{$this->subdistrict_id}", 3600, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'key' => env('RAJAONGKIR_API_KEY'),
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/district/{$this->district_id}");

            if ($response->successful()) {
                $districts = $response->json()['data'] ?? [];
                $district = collect($districts)->firstWhere('id', $this->subdistrict_id);
                return $district['name'] ?? '-';
            }
            return '-';
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
