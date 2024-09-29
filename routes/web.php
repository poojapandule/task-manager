<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


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
    return view('welcome');
});

Route::get('/dbconn', function () {
    return view('connection');
});


// Routes for Authentication
// Route::get('login', function () {
//     return view('auth.login'); // Points to your login view
// })->name('login');

// Route::get('register', function () {
//     return view('auth.register'); // Points to your register view
// })->name('register');

// These routes should post data to the AuthController
//Route::post('login', [AuthController::class, 'login'])->name('login.post');
//Route::post('register', [AuthController::class, 'register'])->name('register.post');





Route::view('/register', 'auth.register');
Route::view('/login', 'auth.login');
Route::view('/tasks', 'tasks.index');
Route::get('/tasks/edit', function () {
    return view('tasks.edit');
});

Route::get('/tasks/view', function () {
    return view('tasks.view');
});