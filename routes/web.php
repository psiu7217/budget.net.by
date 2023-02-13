<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\PurseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StatisticsController;

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


Route::middleware('auth')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/family', [FamilyController::class, 'show'])->name('family.show');
    Route::get('/family/create', [FamilyController::class, 'create'])->name('family.create');
    Route::patch('/family/find', [FamilyController::class, 'find'])->name('family.find');
    Route::patch('/family/update', [FamilyController::class, 'update'])->name('family.update');
    Route::patch('/family/leave', [FamilyController::class, 'leaveFamily'])->name('family.leave');

    Route::resource('purse', PurseController::class);
    Route::resource('income', IncomeController::class);
    Route::resource('group', GroupController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('check', CheckController::class);

    Route::resource('plan', PlanController::class);
    Route::post('/plan/close-month', [PlanController::class, 'closeMonth'])->name('plan.close_month');
    Route::post('/plan/cansel-close-month', [PlanController::class, 'cancelCloseMonth'])->name('plan.cancel_close_month');

    Route::resource('transaction', TransactionController::class);

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});


Route::get('/cron/update-plan', [CronController::class, 'updatePlan'])->name('cron.update_plan');

require __DIR__.'/auth.php';
