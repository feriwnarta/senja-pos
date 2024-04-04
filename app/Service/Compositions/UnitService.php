<?php

namespace App\Service\Compositions;

use App\Contract\Compositions\UnitRepository;
use App\Dto\UnitDTO;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class UnitService implements UnitRepository
{

    private UnitRepository $repository;

    /**
     * @param UnitRepository $repository
     */
    public function __construct(UnitRepository $repository)
    {
        $this->repository = $repository;
    }


    public function create(UnitDTO $unitDTO): Unit
    {
        if (is_null($unitDTO)) {
            report(new Exception(json_encode([
                'message' => 'gagal buat unit baru dikarenakan UNIT DTO NULL',
            ])));
            abort(400);
        }


        try {
            return DB::transaction(function () use ($unitDTO) {
                Log::info(json_encode([
                    'message' => 'Membuat unit baru',
                    'DTO' => $unitDTO
                ]));

                return $this->repository->create($unitDTO);
            });
        } catch (Exception $exception) {
            Log::error(json_encode([
                'message' => 'Gagal membuat unit baru',
                'DTO' => $unitDTO
            ]));
            report($exception);
            abort(400);
        }

    }
}
