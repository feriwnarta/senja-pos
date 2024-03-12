<?php

namespace App\Service\Compositions;

use App\Contract\Compositions\CategoryRepository;
use App\Dto\CategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class CategoryService implements \App\Contract\Compositions\CategoryService
{

    private CategoryRepository $repository;

    /**
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }


    public function createCategory(CategoryDTO $categoryDTO): Category
    {
        if (is_null($categoryDTO)) {
            report(new Exception(json_encode([
                'message' => 'gagal buat category baru dikarenakan CATEGORY DTO NULL',
            ])));
            abort(400);
        }

        try {
            return DB::transaction(function () use ($categoryDTO) {
                Log::info(json_encode([
                    'message' => 'Membuat category baru',
                    'DTO' => $categoryDTO
                ]));

                return $this->repository->create($categoryDTO);
            });
        } catch (Exception $exception) {
            Log::error(json_encode([
                'message' => 'Gagal membuat category baru',
                'DTO' => $categoryDTO
            ]));
            report($exception);
            abort(400);
        }
    }
}
