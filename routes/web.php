<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\TrashNoteController;
use App\Models\Notebook;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::resource('notes', NoteController::class)->middleware('auth');

Route::resource('notebooks', NotebookController::class)->middleware('auth');
Route::get('notebooks/{notebook}/addnote', [NotebookController::class, 'addNote'])->middleware('auth')->name('notebooks.addNote');

Route::prefix('/trash')->name('trash.')->middleware('auth')->group(function () {
    Route::get('/', [TrashNoteController::class, 'index'])->name('index');
    Route::get('/{note}', [TrashNoteController::class, 'show'])->withTrashed()->name('show');
    Route::put('/{note}', [TrashNoteController::class, 'update'])->withTrashed()->name('update');
    Route::delete('/{note}', [TrashNoteController::class, 'destroy'])->withTrashed()->name('destroy');
});
