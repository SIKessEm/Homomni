<?php
const APP_ROOT = __DIR__;
const APP_NAME = 'Homomni';
const APP_BASE = '/';
const APP_ICON = 'icon.png';
const APP_FAVICON = 'favicon.ico';
const APP_LOGO = 'logo.svg';
const APP_COLOR = '#788eff';
const APP_AUTHOR = 'SIGUI Kessé Emmanuel';


function env(string $key, mixed $default = null) {
    static $env;
    if (!isset($env)) {
        $env_options = require __DIR__ . '/env.php';
        $env_file = __DIR__ . '/.env';
        if (!is_file($env_file)) {
            $env_data = '';
            foreach ($env_options as $key => $value) {
                if (is_array($value)) {
                    $env_data .= "[$key]" . PHP_EOL;
                    foreach ($value as $k => $v) {
                        $env_data .= (is_int($k) ? "$v=" : "$k=$v") . PHP_EOL;
                    }
                }
                else {
                    $env_data .= is_int($key) ? "$value=" : "$key=$value" . PHP_EOL;
                }
            }
            file_put_contents($env_file, $env_data);
        }
        $env_data = parse_ini_file($env_file, true, INI_SCANNER_TYPED);
        $env = [];
        foreach ($env_data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $name = strtoupper($key) . '_' . strtoupper($k);
                    $env[$name] = $_ENV = $_SERVER = $v === 'true' ? true : ($v === 'false' ? false : $v);
                    putenv("$name=$v");
                }
            }
            else {
                $name = strtoupper($key);
                $env[$name] = $_ENV = $_SERVER = $value === 'true' ? true : ($value === 'false' ? false : $value);
                putenv("$name=$value");
            }
        }
    }
    return $env[$key] ?? $default;
}