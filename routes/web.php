<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api/');  // Redireciona para a API
});
