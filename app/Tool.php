<?php

namespace App;

use Illuminate\View\Component as ViewComponent;
use Livewire\Component as LivewireComponent;

class Tool
{
    /**
     * Returns a random array item
     *
     * @param array $array
     * @return mixed
     */
    public static function randomItem(array $array): mixed
    {
        if (!empty($array)) {
            return $array[rand(0, count($array) - 1)];
        }
    }

    /**
     * base64 URL encode
     *
     * @param string $data
     * @return string
     */
    public static function base64url_encode(string $data) : string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * base64 URL decode
     *
     * @param string $data
     * @return string
     */
    public static function base64url_decode(string $data) : string
    {
        // return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    /**
     * Encrypt encode ('base_64' or 'laravel')
     *
     * @param string $data, bool $method = 'base_64'
     * @return string
     */
    public static function encode(string $data, string $method = 'base_64') : string
    {
        if ($method == 'base_64') {
            return self::base64url_encode($data);
        } else if($method == 'laravel') { // laravel internal encoder
            return encrypt($data);
        } else {
            return self::base64url_encode($data);
        }
    }
    
    /**
     * Encrypt decode ('base_64' or 'laravel')
     *
     * @param string $data, bool $method = 'base_64'
     * @return string
     */
    public static function decode(string $data, string $method = 'base_64') : string
    {
        if ($method == 'base_64') {
            return self::base64url_decode($data);
        } else if ($method == 'laravel') { // laravel internal encoder
            return decrypt($data);
        } else {
            return self::base64url_decode($data);
        }
    }

    /**
     * Dispatches a Toastr event from '$component'
     * with specific message type
     * and ['title', 'message'] array
     * 
     * Listeners must be installed in main layout
     *
     * @param LivewireComponent|ViewComponent $component
     * @param string $type
     * @param array $message_array
     * @return void
     */
    public static function toastr(LivewireComponent|ViewComponent $component, string $type = 'info', array $message_array): void
    {
        $component->dispatch($type, [ 
            'title' => $message_array['title'] ?? '',
            'message' => $message_array['message'] ?? 'No message', 
        ]);
    }

    /**
     * Finds an array item inside an array
     * by given key and value
     * and returns that [key => value] pair
     *
     * @param array $array_of_arrays
     * @param string $find_key
     * @param [type] $find_value
     * @return array
     */
    public static function findItemByKey(array $array_of_arrays, string $find_key, $find_value): array
    {
        foreach ($array_of_arrays as $key => $array) {
            if (array_key_exists($find_key, $array)) {
                if ($array[$find_key] == $find_value) {
                    return collect([$key => $array])->first();
                }
            }
        }
        return [];
    }

    /**
     * Updates or creates JSON properties values and returns updated JSON string
     *
     * @param string $original_json_data
     * @param array $updated_data_array
     * @return string|false
     */
    public static function updateOrCreateJsonColumns(string $original_json_data, array $updated_data_array): string|false
    {
        $updated_json_columns = json_decode($original_json_data, true);
        foreach ($updated_data_array as $column => $new_value) {
            $updated_json_columns[$column] = $new_value;
        }
        $updated_json_columns = json_encode($updated_json_columns);
        return $updated_json_columns;
    }
}