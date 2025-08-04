<?php

if (!function_exists('parseCurrency')) {
    /**
     * Parse a formatted currency string to a float value.
     *
     * @param string $value
     * @return float
     */
    function parseCurrency($value)
    {
        return floatval(str_replace(['.', ','], ['', '.'], $value));
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format a number to a currency string.
     *
     * @param float $value
     * @return string
     */
    function formatCurrency($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
