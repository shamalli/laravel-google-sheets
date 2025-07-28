<?php

function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $url = setting('google_sheet_url');
        if ($url) {
            $sheetService = app(GoogleSheetService::class);
            $sheetService->setSheetId($url);
            $sheetService->syncData();
        }
    })->everyMinute();
}