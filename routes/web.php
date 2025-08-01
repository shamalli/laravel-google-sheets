<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});
require __DIR__.'/auth.php';

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::middleware(['auth'])->group(function () {
    Route::resource('items', ItemController::class);
    Route::post('items/generate', [ItemController::class, 'generateRandom'])->name('items.generate');
    Route::post('items/clear', [ItemController::class, 'clearAll'])->name('items.clear');
    Route::post('items/set-google-sheet', [ItemController::class, 'setGoogleSheetUrl'])->name('items.set-google-sheet');
});

Route::get('/fetch/{count?}', function ($count = null) {
    $command = ['php', 'artisan', 'google-sheet:fetch'];
    
    if ($count) {
        $command[] = '--count='.$count;
    }
    
    $result = Process::path(base_path())->run($command);
    
    if (!$result->successful()) {
        $result->throw();
    }
    
    return '<pre>'.$result->output().'</pre>';
});