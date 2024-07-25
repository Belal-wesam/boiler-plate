<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

#localization middlewares and prefixing
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {
    #add localized routes here and the prexfix them with admin keyword 
    Route::prefix('admin')->group(function () {
        #auth routes
        Route::view('login', 'admin.auth.login')->name('admin.login_form')->middleware('guest:admin');
        Route::post('login', [AuthController::class, 'login'])->name('admin.login')->middleware('guest:admin');
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth:admin');

        #routes that need authetication to interact with
        Route::group(['middleware' => 'auth:admin', 'as' => 'admin.'], function () {
            #placeholder route 
            Route::view('/', 'admin.pages.index')->name('index');



            #roles routes (prefix is stand alone because of overlapping)
            Route::prefix('roles')->group(function () {
                Route::group(['as' => 'roles.', 'controller' => RoleController::class, 'middleware' => ['can:see roles']], function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::patch('/', 'update')->name('update');
                    Route::get('/role-users', 'getRoleUsers')->name('role_users'); // get role users for datatable
                });
            });


            #admins crud routes (prefix is stand alone because of overlapping)
            Route::prefix('admins')->group(function () {
                Route::group(['as' => 'admins.', 'controller' => AdminController::class, 'middleware' => ['can:see admins']], function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::patch('/', 'update')->name('update');
                    Route::delete('/', 'destroy')->name('delete');
                    Route::get('/admins-list', 'getAdminsList')->name('admins_list'); // get role users for datatable
                });
            });


            #categories crud routes (prefix is stand alone because of overlapping)
            Route::prefix('categories')->group(function () {
                Route::group(['as' => 'categories.', 'controller' => CategoryController::class, 'middleware' => ['can:see categories']], function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::patch('/', 'update')->name('update');
                    Route::delete('/', 'destroy')->name('delete');
                    Route::get('/categories-list', 'getCategoriesList')->name('categories_list'); // get role users for datatable
                });
            });
        });
    });
});
