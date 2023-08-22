<?php

use Illuminate\Support\Facades\Route;
use BasePack\Http\Controllers\Auth\IdservLoginController;

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
    return redirect('login');
});

Route::get('/login', function(){
    return view('basepack::auth');
})->name( 'login' )->middleware('web');

Route::get('/logout', [IdservLoginController::class, 'logout'] )
    ->name( 'logout' );

Route::get('/callback', function(){
    $input = request()->all();
    if(isset($input['tkn'])){
        return redirect("/login?tkn=".$input['tkn']);
    }
})->name('idservcallback');

Route::get('/dashboard', function () {
    return view('basepack::dashboard');
})->name('dashboard')->middleware('web');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard')->middleware('idservAuth');



