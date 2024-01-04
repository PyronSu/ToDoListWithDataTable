<?php

use App\Models\DataTable;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AjaxAgainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('index',[TaskController::class,'index']);

Route::post('store',[TaskController::class,'store']);
Route::post('edit',[TaskController::class,'edit']);
Route::post('delete',[TaskController::class,'delete']);


//AREA
Route::get('again',[AjaxAgainController::class,'show']);
Route::post('storeArea',[AjaxAgainController::class,'storeArea']);
Route::post('editCategory',[AjaxAgainController::class,'editCategory']);
Route::post('deleteCategory',[AjaxAgainController::class,'delete']);


