<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\NomorController;
use App\Http\Controllers\api\ReviewController;
use App\Http\Controllers\api\PropertyController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\LandingpageController;
use App\Http\Controllers\api\CategoryTypeController;
use App\Http\Controllers\api\CategoryLocationController;

//user
Route::get('/', [LandingpageController::class, 'homeIndex'])->name('user.index');
Route::get('/details-property/{id}', [LandingpageController::class, 'detailsIndex'])->name('user.show');
Route::get('/property', [LandingpageController::class, 'propertyIndex'])->name('user.propertyIndex');
Route::get('/contact', [LandingpageController::class, 'contactIndex'])->name('user.contactIndex');

//admin
Route::get('/home-admin', [DashboardController::class, 'countProperties'])->name('admin.dashboard');
Route::post('/landing-page-update', [DashboardController::class, 'insertLandingPage'])->name('landingPage.insert');

//user-admin
Route::get('/user-admin', [UserController::class, 'index'])->name('users.index');
Route::post('/user-create', [UserController::class, 'store'])->name('users.store');
Route::put('/user-update/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/user-delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

//property admin
Route::get('/property-admin', [PropertyController::class, 'index'])->name('property.index');
Route::post('/property-create', [PropertyController::class, 'store'])->name('property.store');
Route::get('/form-property', [PropertyController::class, 'showCategory'])->name('property.showCategory');
Route::get('/property-edit/{id}', [PropertyController::class, 'edit'])->name('property.edit');
Route::put('/property-update/{id}', [PropertyController::class, 'update'])->name('property.update');
Route::delete('/property-delete/{id}', [PropertyController::class, 'destroy'])->name('property.destroy');

//category type-admin
Route::get('/category-admin', [CategoryTypeController::class, 'index'])->name('categorytype.index');
Route::post('/category-create', [CategoryTypeController::class, 'store'])->name('categorytype.store');
Route::put('/category-update/{id}', [CategoryTypeController::class, 'update'])->name('categorytype.update');
Route::delete('/category-delete/{id}', [CategoryTypeController::class, 'destroy'])->name('categorytype.destroy');

//category location-admin
Route::get('/category-location-admin', [CategoryLocationController::class, 'index'])->name('categorylocation.index');
Route::post('/category-location-create', [CategoryLocationController::class, 'store'])->name('categorylocation.store');
Route::put('/category-location-update/{id}', [CategoryLocationController::class, 'update'])->name('categorylocation.update');
Route::delete('/category-location-delete/{id}', [CategoryLocationController::class, 'destroy'])->name('categorylocation.destroy');

//rotator-admin
Route::get('/admin-admin', [NomorController::class, 'index'])->name('rotator.index');
Route::post('/rotator-create', [NomorController::class, 'store'])->name('rotator.store');
Route::put('/rotator-update/{id}', [NomorController::class, 'update'])->name('rotator.update');
Route::delete('/rotator-delete/{id}', [NomorController::class, 'destroy'])->name('rotator.destroy');
Route::get('/show-link/{id?}', [NomorController::class, 'showlink'])->name('rotator.showLink');

//review-admin
Route::get('/review-admin', [ReviewController::class, 'index'])->name('review.index');
Route::post('/review-create', [ReviewController::class, 'store'])->name('review.store');
Route::delete('/review-delete/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

//Auth
Route::get('/ors-login', function () {
    return view('auth/login');
});

Route::get('/ors-register', function () {
    return view('auth/register');
});
