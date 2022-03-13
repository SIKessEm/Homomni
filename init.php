<?php
const APP_ROOT = __DIR__;
const APP_NAME = 'Homomni';
const APP_BASE = '/';
const APP_ICON = 'icon.png';
const APP_FAVICON = 'favicon.ico';
const APP_LOGO = 'logo.svg';
const APP_COLOR = '#788eff';
const APP_AUTHOR = 'SIGUI Kessé Emmanuel';


function env(string $key, mixed $default = null): string {
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
                    $env[$name] = $_ENV[$name] = $_SERVER[$name] = $v === 'true' ? true : ($v === 'false' ? false : $v);
                    putenv("$name=$v");
                }
            }
            else {
                $name = strtoupper($key);
                $env[$name] = $_ENV[$name] = $_SERVER[$name] = $value === 'true' ? true : ($value === 'false' ? false : $value);
                putenv("$name=$value");
            }
        }
    }
    return $env[$key] ?? $default;
}

function style(string $name, bool $use_content = false): string {
    $source_path = APP_ROOT . '/res/styles/' . $name . '.css';
    $target_path = APP_ROOT . '/client/' . $name . '.css';
    if (!is_file($target_path) || (filemtime($source_path) > filemtime($target_path))) {
        ob_start();
        include $source_path;
        $style_content = ob_get_clean();
        file_put_contents($target_path, $style_content);
        touch($target_path, filemtime($source_path));
    }
    if ($use_content) {
        $content = file_get_contents($target_path);
        $style = '<style>' . $content . '</style>';
    }
    else {
        $url = APP_BASE . "client/$name.css?v=" . filemtime($target_path);
        $style = '<link rel="stylesheet" href="' . $url . '.css">';
    }
    return $style;
}

function view(string $name, array $data = []): ?string {
    $path = APP_ROOT . '/res/views/' . $name;
    if (is_dir($path)) {
        $path .= '/index.php';
    }
    if (is_file($path)) {
        ob_start();
        extract($data);
        include $path;
        $view = ob_get_clean();
        return $view;
    }
    return null;
}

function schema(string $name, string $type): ?string {
    $path = APP_ROOT . "/res/schemas/$name.$type";
    if (is_file($path)) {
        return file_get_contents($path);
    }
    return null;
}

function sql(string $name): ?string {
    return schema($name, 'sql');
}

function json(string $name): ?string {
    return schema($name, 'json');
}

spl_autoload_register(function ($name) {
    $name = str_replace('\\', '/', $name);
    if (is_file($file = APP_ROOT . "/src/$name.php")) {
        require_once $file;
        if (class_exists($name)) {
            return;
        }
        throw new \Exception("Class $name not found in $file");
    }
    throw new \Exception("Class $name not found");
});