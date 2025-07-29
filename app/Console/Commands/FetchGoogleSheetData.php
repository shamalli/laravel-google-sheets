<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleSheetService;
use App\Settings\GoogleSettings;

class FetchGoogleSheetData extends Command
{
    protected $signature = 'google-sheet:fetch {--count= : Limit the number of rows to fetch}';
    
    protected $description = 'Fetch data from Google Sheet and display ID and comments';
    
    public function handle(GoogleSheetService $sheetService)
    {
        $url = app(GoogleSettings::class)->google_sheet_url;
        
        if (!$url) {
            $this->error('Google Sheet URL is not set');
            return 1;
        }
        
        $sheetService->setSheetId($url);
        $count = $this->option('count');

        $this->info("Fetching data from Google Sheet...");
        
        $data = $sheetService->fetchSheetData($count);
        $total = count($data);
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        foreach ($data as $row) {
            $this->newLine();
            $this->line("ID: {$row['ID']} | Comment: {$row['comment']}");
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Fetched {$total} records.");
        
        return 0;
    }
}