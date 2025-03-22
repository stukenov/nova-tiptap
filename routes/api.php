<?php

use Illuminate\Support\Facades\Route;
use STukenov\Tiptap\Controllers\FilesController;
use STukenov\Tiptap\Controllers\ImagesController;

Route::post('file', FilesController::class.'@store');
Route::post('image', ImagesController::class.'@store');
