<?php

namespace App\Utils;

class EnvLoader
{
    public static function load(): void
    {
        $envContent = file_get_contents(__DIR__ . '/../../.env');

        preg_match_all('/^([A-Z0-9_]+)=(.*)$/m', $envContent, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            putenv(trim($match[1]) . '=' . trim($match[2]));
        }
    }
}
