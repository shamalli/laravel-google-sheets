<?php

namespace App\Services;

use App\Models\Item;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    protected $client;
    protected $service;
    protected $sheetId;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/credentials.json'));
        $this->client->addScope(Sheets::SPREADSHEETS);
        $this->service = new Sheets($this->client);
    }
    
    public function setSheetId($url)
    {
        // Extract sheet ID from URL
        preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);
        $this->sheetId = $matches[1] ?? null;
    }
    
    public function syncData()
    {
        if (!$this->sheetId) {
            Log::error('Google Sheet ID not set');
            return;
        }
        
        // Get current data from sheet to preserve comments
        $currentData = $this->getCurrentSheetData();
        $comments = $this->extractComments($currentData);
        
        // Get allowed items from database
        $items = Item::allowed()->get();
        
        // Prepare data for upload
        $values = [];
        $headers = ['ID', 'Name', 'Description', 'Status', 'Created At', 'Updated At'];
        $values[] = $headers;
        
        foreach ($items as $item) {
            $values[] = [
                $item->id,
                $item->name,
                $item->description,
                $item->status->value,
                $item->created_at,
                $item->updated_at,
            ];
        }
        
        // Add comments back to the data
        foreach ($values as $index => &$row) {
            if ($index === 0) continue; // Skip header
            
            $id = $row[0]; // First column is ID
            if (isset($comments[$id])) {
                // Add comment column (n+1 where n is data columns)
                $row[] = $comments[$id];
            }
        }
        
        // Update the sheet
        $range = 'A1'; // Start from top-left
        $body = new ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        $this->service->spreadsheets_values->update(
            $this->sheetId,
            $range,
            $body,
            $params
        );
    }
    
    protected function getCurrentSheetData()
    {
        try {
            $response = $this->service->spreadsheets_values->get($this->sheetId, 'A1:Z');
            return $response->getValues() ?? [];
        } catch (\Exception $e) {
            Log::error('Error getting current sheet data: ' . $e->getMessage());
            return [];
        }
    }
    
    protected function extractComments($sheetData)
    {
        $comments = [];
        $headers = $sheetData[0] ?? [];
        $commentColumnIndex = count($headers); // n+1 column
        
        foreach ($sheetData as $index => $row) {
            if ($index === 0) continue; // Skip header
            
            $id = $row[0] ?? null;
            $comment = $row[$commentColumnIndex] ?? null;
            
            if ($id && $comment) {
                $comments[$id] = $comment;
            }
        }
        
        return $comments;
    }
    
    public function fetchSheetData($limit = null)
    {
        if (!$this->sheetId) {
            return [];
        }
        
        $range = 'A1:Z';
        $response = $this->service->spreadsheets_values->get($this->sheetId, $range);
        $values = $response->getValues() ?? [];
        
        $headers = $values[0] ?? [];
        $data = [];
        
        foreach ($values as $index => $row) {
            if ($index === 0) continue; // Skip header
            
            $item = [];
            foreach ($headers as $colIndex => $header) {
                $item[$header] = $row[$colIndex] ?? null;
            }
            
            // Get comment from n+1 column
            $commentColumn = count($headers);
            $item['comment'] = $row[$commentColumn] ?? null;
            
            $data[] = $item;
            
            if ($limit && count($data) >= $limit) {
                break;
            }
        }
        
        return $data;
    }
}