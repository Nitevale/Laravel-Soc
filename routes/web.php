<?php

use App\Http\Controllers\pagecontroller;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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
    return view('home-guest');
});

Route::get('/admins-only', function() {
    return 'Admin Ka!';
})->middleware('can:visitAdminPage, post'); 


// User Routes
Route::get('/', [pagecontroller::class, "showcorrecthomepage"])->name('login');
Route::post('/register', [pagecontroller::class, "register"]);
Route::post('/login', [pagecontroller::class, "login"]);
Route::post('/logout', [pagecontroller::class, "logout"]);
Route::get('/manage-avatar', [pagecontroller::class, "showAvatarForm"])->middleware('MustBeLoggedIn');
Route::post('/manage-avatar', [pagecontroller::class, "storeAvatar"])->middleware('MustBeLoggedIn');

// Posting Routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('MustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeData'])->middleware('MustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->middleware('MustBeLoggedIn');

Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

// Profile Routes
Route::get('/profile/{user:username}', [pagecontroller::class, 'profile']);