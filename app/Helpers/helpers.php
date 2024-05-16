<?php

if (! function_exists('array_keys_to_camel')) {
    /**
     * Convert all array keys to camelCase
     *
     * @param  array  $array
     * @return array
     */
    function array_keys_to_camel(array $array): array
    {
        $convertedArray = [];
        foreach ($array as $key => $value) {
            $convertedKey = \Illuminate\Support\Str::camel($key);
            if (is_array($value)) {
                $value = array_keys_to_camel($value);
            }
            $convertedArray[$convertedKey] = $value;
        }

        return $convertedArray;
    }
}
