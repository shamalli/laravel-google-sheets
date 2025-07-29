<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\GoogleSheetService;
use App\Settings\GoogleSettings;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $url = app(GoogleSettings::class)->google_sheet_url;
        if ($url) {
        $sheetService = app(GoogleSheetService::class);
        $sheetService->setSheetId($url);
        $sheetService->syncData();
    }
})->everyMinute();