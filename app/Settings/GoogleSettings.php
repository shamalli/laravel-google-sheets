<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GoogleSettings extends Settings
{
    public string $google_sheet_url;
    
    public static function group(): string
    {
        return 'google';
    }
}