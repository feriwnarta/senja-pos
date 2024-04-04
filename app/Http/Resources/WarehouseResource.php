<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $areasAndRack = [];

        foreach ($this->areas as $area) {
            $name = $area->name;

            $racks = [];
            foreach ($area->racks as $rack) {
                 $racks[] = $rack->name;
            }

            $areasAndRack[] = [
                'area' => $name,
                'racks' => $racks
            ];
        }


        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'areasAndRacks' => $areasAndRack,
            'address' => $this->address,
        ];
    }
}
