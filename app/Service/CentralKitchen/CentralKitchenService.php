<?php

namespace App\Service\CentralKitchen;

use App\Contract\CentralKitchen\CentralKitchenRepository;
use App\Dto\CentralKitchenDTO;
use App\Http\Resources\CentralKitchenResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CentralKitchenService implements \App\Contract\CentralKitchen\CentralKitchenService
{

    private CentralKitchenRepository $centralKitchenRepository;

    /**
     * @param CentralKitchenRepository $centralKitchenRepository
     */
    public function __construct(CentralKitchenRepository $centralKitchenRepository)
    {
        $this->centralKitchenRepository = $centralKitchenRepository;
    }


    public function create(?CentralKitchenDTO $centralKitchenDTO)
    {
        try {

            if (is_null($centralKitchenDTO)) {
                abort(400);
            }

            $result = null;

            DB::transaction(function () use ($centralKitchenDTO, &$result) {
                $result = $this->centralKitchenRepository->create($centralKitchenDTO);
            });

            Log::info('success');

            return $result;
        }catch (\Exception $exception) {
            report($exception);
            abort(400);
        }
    }
}
