<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Services\GoogleSheetService;
use Spatie\LaravelSettings\Settings;

class GoogleSettings extends Settings
{
    public string $google_sheet_url;
    
    public static function group(): string
    {
        return 'google';
    }
}

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->paginate(10);
        $google_sheet_url = app(GoogleSettings::class)->google_sheet_url;
        return view('items.index', compact('items') + ['google_sheet_url' => $google_sheet_url]);
    }
    
    public function create()
    {
        return view('items.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited',
        ]);
        
        Item::create($validated);
        
        return redirect()->route('items.index');
    }
    
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }
    
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited',
        ]);
        
        $item->update($validated);
        
        return redirect()->route('items.index');
    }
    
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index');
    }
    
    public function generateRandom()
    {
        $statuses = [Status::Allowed->value, Status::Prohibited->value];
        
        for ($i = 0; $i < 1000; $i++) {
            Item::create([
                'name' => 'Item ' . uniqid(),
                'description' => 'Random description ' . rand(1000, 9999),
                'status' => $statuses[rand(0, 1)],
            ]);
        }
        
        return redirect()->route('items.index')->with('success', '1000 items generated!');
    }
    
    public function clearAll()
    {
        Item::truncate();
        return redirect()->route('items.index')->with('success', 'All items deleted!');
    }
    
    public function setGoogleSheetUrl(GoogleSettings $settings, Request $request)
    {
        $request->validate([
            'google_sheet_url' => 'required|url',
        ]);
        
        $settings->google_sheet_url = $request->google_sheet_url;
        $settings->save();
        
        return redirect()->route('items.index')->with('success', 'Google Sheet URL saved!');
    }
}