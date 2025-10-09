<?php

if (!function_exists('euro')) {
    /**
     * Konversi IDR ke Euro dan format tampilan.
     * @param int|float $idr
     * @param float|null $euroRate
     * @param int $decimals
     * @return string
     */
    function euro($euroRate = null, $decimals = 2)
    {
        $rate = $euroRate ?: (app('view')->getShared()['euroRate'] ?? 18500);
        return '€' . number_format($rate, $decimals, ',', '.');
    }
}
