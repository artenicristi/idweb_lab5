<?php

use App\Http\Controllers\PilotageController;
use App\Http\Controllers\ReclamationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home.presentation');
});

Route::get('/home', function () {
    return view('home.home');
})->name('home');

Route::group(['prefix' => '/reclamations'], function () {
    Route::get('', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/create', [ReclamationController::class, 'create'])->name('reclamations.create');
    Route::post('', [ReclamationController::class, 'store'])->name('reclamations.store');
    Route::get('/{reclamation}/edit', [ReclamationController::class, 'edit'])->name('reclamations.edit');
    Route::post('/{reclamation}/comment', [ReclamationController::class, 'addComment'])->name('reclamations.comment');
    Route::get('/{reclamation}/comment', [ReclamationController::class, 'getComments'])->name('reclamations.comments.list');
    Route::put('/{reclamation}', [ReclamationController::class, 'update'])->name('reclamations.update');
    Route::delete('/{reclamation}', [ReclamationController::class, 'destroy'])->name('reclamations.destroy');
});

Route::get('/pilotage/export', [PilotageController::class, 'export'])->name('pilotage.export');
