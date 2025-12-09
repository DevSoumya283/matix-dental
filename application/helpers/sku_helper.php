<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_option_from_sku')) {
    /**
     * Extracts option part from SKU code (e.g. sku5346534-blue-m → Blue, M)
     *
     * @param string $sku SKU code
     * @return string Formatted option part (e.g. "Blue, M") or empty string
     */
    function get_option_from_sku($sku) {
        if (empty($sku)) {
            return '';
        }

        // Match pattern like sku5346534-blue-m
        if (preg_match('/\d+-(.+)$/', $sku, $matches)) {
            $optionPart = $matches[1]; // blue-m
            // Replace hyphens with commas and capitalize words
            $optionCode = ucwords(str_replace('-', ', ', $optionPart)); // Blue, M
            return $optionCode;
        }

        return '';
    }
}
