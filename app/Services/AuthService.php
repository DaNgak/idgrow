<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\Auth;

class AuthService 
{
    private $user;
    private $time;
    
    public function __construct(User $user)
    {
        $this->time = Carbon::now();
        $this->user = $user;
    }

    /**
     * Login user and create Sanctum token
     * 
     * @param array $data
     * @return array
     * @throws UnauthorizedException
     */
    public function login(array $data): array
    {
        // Attempt login dengan email dan password
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            // throw new UnauthorizedException("Credentials are incorrect");
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Kredensial tidak ditemukan!',
                ]
            ];
        }

        // Ambil data user yang sudah login
        $user = Auth::user();

        // Buat token dengan expired dalam 24 jam
        $expiresAt =  $this->time->addDay();
        $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;

        return [
            'status' => true,
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    // 'role' => $user->role->name ?? 'User',
                ]
            ]
        ];
    }

    /**
     * Register new user
     * 
     * @param array $data
     * @return User
     */
    public function register(array $data): array
    {
        // Hash password
        $data['password'] = Hash::make($data['password']);
        // Create user dari model User
        $user = $this->user->create($data);
        // Return jika tidak ada user
        if (!$user) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal membuat data user!',
                ]
            ];
        }
        // Return jika berhasil
        return [
            'status' => true,
            'data' => [
                'id' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                // 'role' => $user->role->name ?? 'User',
                'created_at' => $user->created_at->format('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Logout user and revoke current token
     * 
     * @return bool
     */
    public function logout(): array
    {
        // Check apakah ada tidak ada auth user
        if (!auth()->check()) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Authenticated User tidak ditemukan!',
                ]
            ];
        }
        
        auth()->user()->currentAccessToken()->delete();

        return [
            'status' => true,
            'data' => null
        ];
    }

    /**
     * Get authenticated user
     * 
     * @return User|null
     */
    public function getAuthenticatedUser(): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Authenticated User tidak ditemukan!',
                ]
            ];
        }

        return [
            'status' => true,
            'data' => [
                'id' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                // 'role' => $user->role->name ?? 'User',
                'created_at' => $user->created_at->format('Y-m-d H:i:s')
            ]
        ];
    }
}