<?php
use Processton\ProcesstonUser\Http\Controllers\ProcesstonUserController;
use Processton\ProcesstonUser\Http\Controllers\ProcesstonRoleController;
use Processton\ProcesstonUser\Http\Controllers\ProcesstonUserStatsController;
use Processton\ProcesstonUser\Http\Controllers\ProcesstonPermissionController;


Route::middleware([
    'web'
])->group(function () {

    Route::get('/list', [ProcesstonUserController::class, 'index'])->name('processton-app-user.index');
    Route::any('/invite', [ProcesstonUserController::class, 'invite'])->name('processton-app-user.invite');
    Route::any('/block', [ProcesstonUserController::class, 'blockUser'])->name('processton-app-user.block');
    Route::any('/un-block', [ProcesstonUserController::class, 'allowUser'])->name('processton-app-user.un_block');
    Route::any('/change-role', [ProcesstonUserController::class, 'changeRole'])->name('processton-app-user.change_role');
    

    Route::prefix('role')->group(function () {
        Route::get('/list', [ProcesstonRoleController::class, 'index'])->name('processton-app-user-roles.index');
        Route::any('/create', [ProcesstonRoleController::class, 'addRole'])->name('processton-app-user-roles.create');
        Route::any('/edit', [ProcesstonRoleController::class, 'editRole'])->name('processton-app-user-roles.edit');
        Route::any('/permissions', [ProcesstonPermissionController::class, 'index'])->name('processton-app-user-roles.permissions');
        Route::get('/scan-permissions', [ProcesstonPermissionController::class, 'scanForPermissions'])->name('processton-app-user-roles.scan_permissions');
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