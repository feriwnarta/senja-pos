<?php

namespace App\Dto;

enum WarehouseEnum {

    case CENTRAL;
    case OUTLET;
}

class WarehouseDTO
{
    private string $id;

    private string $sourceId;
    private ?string $code;
    private ?string $name;
    private array $areasAndRack = [];

    private ?string $address = null;

    private ?WarehouseEnum $enum;

    /**
     * @param string $sourceId
     * @param string|null $code
     * @param string|null $name
     * @param array $areasAndRack
     * @param string|null $address
     * @param WarehouseEnum|null $enum
     */
    public function __construct(string $sourceId, ?string $code, ?string $name, array $areasAndRack, ?string $address, ?WarehouseEnum $enum)
    {
        $this->sourceId = $sourceId;
        $this->code = $code;
        $this->name = $name;
        $this->areasAndRack = $areasAndRack;
        $this->address = $address;
        $this->enum = $enum;
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    public function setSourceId(string $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAreasAndRack(): array
    {
        return $this->areasAndRack;
    }

    public function setAreasAndRack(array $areasAndRack): void
    {
        $this->areasAndRack = $areasAndRack;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getEnum(): ?WarehouseEnum
    {
        return $this->enum;
    }

    public function setEnum(?WarehouseEnum $enum): void
    {
        $this->enum = $enum;
    }




}
