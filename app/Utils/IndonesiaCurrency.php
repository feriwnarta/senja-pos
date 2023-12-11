<?php

namespace App\Utils;

class IndonesiaCurrency
{

    static function formatToRupiah(float $rupiah): string
    {
        return "Rp " . number_format($rupiah, 0, ',', '.');
    }
}
