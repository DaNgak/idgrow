<?php

namespace App\Http\Controllers\Api\V1;

use App\Commons\Traits\BaseApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use BaseApiResponse;

    /**
     * 
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController Constructor
     *
     * @param AuthService $authService
     *
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Membuat validasi request
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            // Cek jika validasi gagal
            if ($validator->fails()) {
                return $this->apiError(422, 'Validation Errors', $validator->errors());
            }

            // Ambil return result dari auth service
            $result = $this->authService->login($request->all());

            // Jika gagal melakukan cek login
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }
            
            // Return api success dengan data dari return auth service
            return $this->apiSuccess(200, 'Login berhasil', $result['data']);
        } catch (\Throwable $th) {
            // Simpan error di log dengan type critical
            Log::critical('API Login Error: ', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
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
     * Register user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Membuat validasi request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed'
            ]);

            // Cek jika validasi gagal
            if ($validator->fails()) {
                return $this->apiError(422, 'Validation Errors', $validator->errors());
            }

            // Ambil return result dari auth service
            $result = $this->authService->register($request->all());

            // Jika gagal melakukan cek register
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Return api success dengan data dari return auth service
            return $this->apiSuccess(201, 'Registrasi berhasil, silahkan lakukan login!', $result['data']);
        } catch (\Throwable $th) {
            // Simpan error di log dengan type critical
            Log::critical('API Registration Error: ', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                "trace" => $th->getTraceAsString()
            ]);

            // Set error message default
            $errorMessage = 'Terjadi kesalahan pada server!';

            // Set error message jika env nya local
            if (env('APP_ENV') === 'local' && env('APP_DEBUG')) {
                $errorMessage = 'Terjadi kesalahan pada server: ' . $th->getMessage();
            }

            // Return api error dengan error message nya
            return $this->apiError(500, $errorMessage);
        }
    }

    /**
     * Logout user
     * 
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Panggil method logout dari authService
            $result = $this->authService->logout();

            // Jika gagal melakukan cek register
            if (!$result['status']) {
                return $this->apiError($result['response']['code'], $result['response']['message']);
            }

            // Return api success
            return $this->apiSuccess(200, 'Logout berhasil', $result['data']);
        } catch (\Throwable $th) {
            // Simpan error di log dengan type critical
            Log::critical('API Logout Error: ', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
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
     * Get profile of Authenticated User
     * 
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        // Cek apakah request ajax
        if (!$request->ajax()) {
            return $this->apiError(400, 'Invalid Request!');
        }

        try {
            // Ambil data user yang sedang login dari auth service
            $user = $this->authService->getAuthenticatedUser();

            if (!$user) {
                return $this->apiError(404, 'Auth user tidak ditemukan!');
            }

            // Return api success dengan data user
            return $this->apiSuccess(200, 'Berhasil mendapatkan data', $user);
        } catch (\Throwable $th) {
            // Simpan error di log dengan type critical
            Log::critical('API Profile Error: ', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
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
}
