<?php

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency.
     *
     * @param  float  $number
     * @param  string  $currency
     * @return string
     */
    function format_currency(float $number): string
    {
        return 'Rp ' . number_format($number, 2, ',', '.');
    }
}