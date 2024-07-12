<?php
use Processton\ProcesstonUser\Http\Controllers\ProcesstonUserController;
use Processton\ProcesstonUser\Http\Controllers\ProcesstonRoleController;
use Processton\ProcesstonUser\Http\Controllers\ProcesstonUserStatsController;


Route::middleware([
    'web'
])->group(function () {

    Route::get('/list', [ProcesstonUserController::class, 'index'])->name('processton-app-user.index');
    Route::any('/invite', [ProcesstonUserController::class, 'invite'])->name('processton-app-user.invite');
    Route::any('/block', [ProcesstonUserController::class, 'blockUser'])->name('processton-app-user.block');
    Route::any('/un-block', [ProcesstonUserController::class, 'allowUser'])->name('processton-app-user.un_block');

    Route::prefix('role')->group(function () {
        Route::get('/list', [ProcesstonRoleController::class, 'index'])->name('processton-app-user-roles.index');
        Route::get('/create', [ProcesstonRoleController::class, 'addRole'])->name('processton-app-user-roles.create');
        Route::get('/edit', [ProcesstonRoleController::class, 'editRole'])->name('processton-app-user-roles.edit');
    });
    
    Route::group(['prefix' => 'stats'], function () {
        Route::get('/count', [ProcesstonUserStatsController::class, 'usersCount'])->name('processton-app-user.stats.count');
        Route::get('/count_new', [ProcesstonUserStatsController::class, 'newUsersCount'])->name('processton-app-user.stats.count_new');
        Route::get('/count_sessions', [ProcesstonUserStatsController::class, 'sessionsCount'])->name('processton-app-user.stats.count_sessions');
        Route::get('/count_pending_validations', [ProcesstonUserStatsController::class, 'pendingValidations'])->name('processton-app-user.stats.count_pending_validations');
        Route::get('/count_session_duration', [ProcesstonUserStatsController::class, 'sessionsDuration'])->name('processton-app-user.stats.count_session_duration');
    });
    
});


Route::middleware([
    'api'
])->group(function () {

    Route::get('/list', [ProcesstonUserController::class, 'index'])->name('processton-app-user.index');


});