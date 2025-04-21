<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Form\FormController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AuthController::class, 'viewLogin'])->name('view.login');
Route::get('/login', [AuthController::class, 'viewLogin'])->name('view.login');
Route::post('/login', [AuthController::class, 'login'])->name('post.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('form', FormController::class)->except('show');
    Route::get('/form/preview/{id}', [FormController::class, 'preview'])->name('form.preview');
    Route::get('/form/show/{id}', [FormController::class, 'show'])->name('form.show');
    Route::get('/form/list', [FormController::class, 'fetchForms'])->name('form.list');
    Route::post('/form/draft', [FormController::class, 'draft'])->name('post.form.draft');
//    Route::get('/form/{form}/edit', [FormController::class, 'edit'])->name('form.edit');
});
