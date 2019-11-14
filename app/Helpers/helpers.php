<?php
if (!function_exists('set_env_var')) {
    function set_env_var($key, $value)
    {
        if (! is_bool(strpos($value, ' '))) {
            $value = '"' . $value . '"';
        }
        $key = strtoupper($key);

        $envPath = app()->environmentFilePath();
        $contents = file_get_contents($envPath);

        preg_match("/^{$key}=[^\r\n]*/m", $contents, $matches);

        $oldValue = count($matches) ? $matches[0] : '';

        if ($oldValue) {
            $contents = str_replace("{$oldValue}", "{$key}={$value}", $contents);
        } else {
            $contents = $contents . "\n{$key}={$value}\n";
        }

        $file = fopen($envPath, 'w');
        fwrite($file, $contents);
        return fclose($file);
    }
}

if (!function_exists('set_env_vars')) {
    function set_env_vars($data)
    {
        foreach($data as $key => $value) {
            set_env_var($key, $value);
        }

        return true;
    }
}

if (!function_exists('is_json')) {
    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
