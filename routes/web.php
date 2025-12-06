<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $milwad = app('milwad');
    dd($milwad);
});



// Route::get('/notes', function () {
//     return view('notes.index');  
// })->name('notes.index');