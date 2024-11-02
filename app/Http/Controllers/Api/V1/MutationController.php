<?php

namespace App\Http\Controllers\Api\V1;

use App\Commons\Traits\BaseApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MutationRequest;
use App\Http\Resources\Api\V1\MutationResource;
use App\Models\Mutation;
use App\Services\MutationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MutationController extends Controller
{
    use BaseApiResponse;

    /**
     * @var \App\Services\MutationService
     */
    protected $mutationService;

    /**
     * MutationController Constructor
     *
     * @param \App\Services\MutationService $mutationService
     */
    public function __construct(MutationService $mutationService)
    {
        $this->mutationService = $mutationService;
    }

    /**
     * Mutation index.
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
            // Panggil mutation service untuk logic
            $result = $this->mutationService->index($request->all());
            
            // Jika gagal mengambil data mutasi
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }
            
            // Gunakan resource untuk merapikan response
            MutationResource::collection($result['data']);

            // Return response success dan result data dari service
            return $this->apiSuccess(200, 'Berhasil mendapatkan data', $result['data']);
        } catch (\Throwable $th) {
            Log::critical("API Fetching Mutations Index Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Set error message default
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            return $this->apiError(500, $errorMessage);
        }
    }

    /**
     * Store new mutation.
     * 
     * @param MutationRequest $request
     * @return JsonResponse
     */
    public function store(MutationRequest $request): JsonResponse
    {
        try {
            // Memanggil service untuk menyimpan data mutasi
            $result = $this->mutationService->store($request->all());

            // Cek apakah ada error saat melakukan proses logic
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Return response success dan result data dari service
            return $this->apiSuccess(201, 'Mutasi berhasil ditambahkan', new MutationResource($result['data']));
        } catch (\Throwable $th) {
            Log::critical("API Creating Mutation Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Set error message default
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Return response api error dengan error message
            return $this->apiError(500, $errorMessage);
        }
    }


    /**
     * Show mutation details.
     * 
     * @param Request $request
     * @param Mutation $mutation
     * @return JsonResponse
     */
    public function show(Request $request, Mutation $mutation): JsonResponse
    {
        // Cek apakah request ajax
        if (!$request->ajax()) {
            return $this->apiError(400, 'Invalid Request!');
        }
        
        // Return response success dengan data dari model binding mutation
        return $this->apiSuccess(200, 'Berhasil mendapatkan data', new MutationResource($mutation->load(['user', 'product'])));
    }

    /**
     * Update mutation data.
     * 
     * @param MutationRequest $request
     * @param Mutation $mutation
     * @return JsonResponse
     */
    public function update(MutationRequest $request, Mutation $mutation): JsonResponse
    {
        try {
            // Memanggil service untuk update data mutasi
            $result = $this->mutationService->update($mutation, $request->all());

            // Cek apakah ada error saat melakukan proses logic
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Return response success dan result data dari service
            return $this->apiSuccess(200, 'Mutasi berhasil diperbarui', new MutationResource($result['data']));
        } catch (\Throwable $th) {
            Log::critical("API Updating Mutation Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Set error message default
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Return response api error dengan error message
            return $this->apiError(500, $errorMessage);
        }
    }

    /**
     * Delete mutation data.
     * 
     * @param Mutation $mutation
     * @return JsonResponse
     */
    public function destroy(Mutation $mutation): JsonResponse
    {
        try {
            // Memanggil service untuk menghapus data mutasi
            $result = $this->mutationService->destroy($mutation);

            // Cek apakah ada error saat melakukan proses logic
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Return response success dan result data dari service
            return $this->apiSuccess(200, 'Mutasi berhasil dihapus');
        } catch (\Throwable $th) {
            Log::critical("API Deleting Mutation Error: ", [
                "message" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Set error message default
            $errorMessage = 'Terjadi kesalahan pada server!';
            if (env('APP_ENV') == 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Return response api error dengan error message
            return $this->apiError(500, $errorMessage);
        }
    }
}
