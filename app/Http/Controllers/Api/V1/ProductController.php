<?php

namespace App\Http\Controllers\Api\V1;

use App\Commons\Traits\BaseApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Services\AuthService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use BaseApiResponse;

    /**
     * 
     * @var ProductService
     */
    protected $productService;

    /**
     * AuthController Constructor
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Product index.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Cek apakah request ajax
        if (!$request->ajax()) {
            return $this->apiError(400, 'Invalid Request!');
        }

        try {
            $result = $this->productService->index($request->all());
            
            // Jika gagal melakukan cek register
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }
            
            // Gunakan resource untuk merapikan response
            ProductResource::collection($result['data']);

            // Return response success dan result data dari service
            return $this->apiSuccess(200, 'Berhasil mendapatkan data', $result['data']);
        } catch (\Throwable $th) {
            Log::critical("API Fetching Products Index Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Set error message default
            $errorMessage = 'Terjadi kesalahan pada server!';

            // Set error message jika env nya local
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Return api error dengan error message nya
            return $this->apiError(500, $errorMessage);
        }
    }

    /**
     * Create new product.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request)
    {
        try {
            // Memanggil service untuk menyimpan data produk
            $result = $this->productService->store($request->all());

            // Cek apakah ada error saat penyimpanan
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Mengembalikan response sukses dengan data produk yang baru disimpan
            return $this->apiSuccess(201, 'Produk berhasil ditambahkan', new ProductResource($result['data']));
        } catch (\Throwable $th) {
            // Logging untuk error yang terjadi
            Log::critical("API Creating Product Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Menentukan pesan error default atau menyesuaikan jika di lingkungan lokal
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Mengembalikan response error
            return $this->apiError(500, $errorMessage);
        }
    }

    /**
     * Show detail product.
     * 
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Request $request, Product $product)
    {
        // Cek apakah request ajax
        if (!$request->ajax()) {
            return $this->apiError(400, 'Invalid Request!');
        }
        
        // Return response success dengan data dari model binding
        return $this->apiSuccess(200, 'Berhasil mendapatkan data produk', new ProductResource($product));
    }

    /**
     * Update data product.
     * 
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            // Memanggil service untuk memperbarui data produk
            $result = $this->productService->update($product, $request->all());

            // Cek apakah ada error saat memperbarui data
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Mengembalikan response sukses dengan data produk yang telah diperbarui
            return $this->apiSuccess(200, 'Produk berhasil diperbarui', new ProductResource($result['data']));
        } catch (\Throwable $th) {
            // Logging untuk error yang terjadi
            Log::critical("API Updating Product Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Menentukan pesan error default atau menyesuaikan jika di lingkungan lokal
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Mengembalikan response error
            return $this->apiError(500, $errorMessage);
        }
    }

    /**
     * Delete data product.
     * 
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            // Memanggil service untuk menghapus data produk
            $result = $this->productService->destroy($product);

            // Cek apakah ada error saat penghapusan
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Mengembalikan response sukses tanpa data karena produk telah dihapus
            return $this->apiSuccess(200, 'Produk berhasil dihapus');
        } catch (\Throwable $th) {
            // Logging untuk error yang terjadi
            Log::critical("API Deleting Product Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Menentukan pesan error default atau menyesuaikan jika di lingkungan lokal
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Mengembalikan response error
            return $this->apiError(500, $errorMessage);
        }
    }
}
