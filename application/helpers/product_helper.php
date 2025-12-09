<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('convert_options_to_list')) {
    function convert_options_to_list($options) {
        $result = [];

        foreach ($options as $attrName => $items) {
            if (!empty($items) && isset($items[0]['value'])) {
                // Take value and make first letter small
                $value = $items[0]['value'];
                $value = lcfirst($value);  // <-- first letter small

                $result[] = $value;
            }
        }

        return $result;
    }
}
