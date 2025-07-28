<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class google_sheet_url extends Settings
{

    public static function group(): string
    {
        return 'google';
    }
}