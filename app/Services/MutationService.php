<?php

namespace App\Services;

use App\Models\Mutation;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class MutationService 
{
    private $mutation;
    private $time;

    public function __construct(Mutation $mutation)
    {
        $this->time = Carbon::now();
        $this->mutation = $mutation;
    }

    /**
     * Index Data
     * 
     * @param array $data
     * @return array
     */
    public function index(array $data): array
    {
        $search = $data['search'] ?? '';

        $data = $this->mutation->query()
            ->with(['user', 'product'])
            ->when($search, function ($query, $search) {
                $query->where('uuid', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%')
                    ->orWhere('quantity', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return [
            'status' => true,
            'data' => $data
        ];
    }

    /**
     * Store new Mutation
     * 
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        $product = Product::select(['id'])->where('uuid', $data['product_id'])->first();
        $user = User::select(['id'])->where('uuid', $data['user_id'])->first();

        $data['product_id'] = $product->id;
        $data['user_id'] = $user->id;

        $result = $this->mutation->create($data);

        if (!$result) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal menambahkan data mutasi!',
                ]
            ];
        }

        return [
            'status' => true,
            'data' => $result
        ];
    }

    /**
     * Update Mutation Data
     * 
     * @param Mutation $mutation
     * @param array $data
     * @return array
     */
    public function update(Mutation $mutation, array $data): array
    {
        $result = $mutation->update($data);

        if (!$result) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal memperbarui data mutasi!',
                ]
            ];
        }

        return [
            'status' => true,
            'data' => $mutation->refresh()
        ];
    }

    /**
     * Delete Mutation Data
     * 
     * @param Mutation $mutation
     * @return array
     */
    public function destroy(Mutation $mutation): array
    {
        $result = $mutation->delete();

        if (!$result) {
            return [
                'status' => false,
                'response' => [
                    'code' => 400,
                    'message' => 'Gagal menghapus data mutasi!',
                ]
            ];
        }

        return [
            'status' => true,
            'data' => null
        ];
    }
}