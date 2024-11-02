<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\Auth;

class ProductService 
{
    private $product;
    private $time;
    
    public function __construct(Product $product)
    {
        $this->time = Carbon::now();
        $this->product = $product;
    }

    /**
     * Index Data
     * 
     * @param array $data
     * @return array
     */
    public function index(array $data): array
    {
        // Ambil input pencarian
        $search = $data['search'] ?? '';

        // Filter dan ambil data produk menggunakan `when`
        $data = $this->product->query()
            ->when($search, function ($query, $search) {
                $query->where('uuid', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return [
            'status' => true,
            'data' => $data
        ];
    }

    /**
     * Store new Product
     * 
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        // Create product dari model Product
        $result = $this->product->create($data);

        // Return jika gagal create product
        if (!$result) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal melakukan create data produk!',
                ]
            ];
        }

        // Return jika berhasil
        return [
            'status' => true,
            'data' => $result
        ];
    }

    /**
     * Update data Product
     * 
     * @param Product $product
     * @param array $data
     * @return array
     */
    public function update(Product $product, array $data): array
    {
        // Update product dari model Product
        $result = $product->update($data);

        // Return jika gagal update product
        if (!$result) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal melakukan update data produk!',
                ]
            ];
        }

        // Return jika berhasil
        return [
            'status' => true,
            'data' => $product->refresh()
        ];
    }

    /**
     * Delete data Product
     * 
     * @param Product $product
     * @return array
     */
    public function destroy(Product $product): array
    {
        // Delete product dari model Product
        $result = $product->delete();

        // Return jika gagal delete product
        if (!$result) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal melakukan delete data produk!',
                ]
            ];
        }

        // Return jika berhasil
        return [
            'status' => true,
            'data' => null
        ];
    }

}