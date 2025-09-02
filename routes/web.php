<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

// Login routes
Route::get('/', function () {
    return view('login');
});
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Client dashboard and routes
Route::middleware(['web', 'auth', \App\Http\Middleware\RoleMiddleware::class . ':client'])->group(function () {
    Route::get('/dashboard/client', function () {
        return view('dashboards.client');
    })->name('dashboard.client');
    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
    Route::get('/incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
    Route::patch('/incidents/{incident}/status', [IncidentController::class, 'updateStatus'])->name('incidents.updateStatus');
});

// Allow both clients and employees to post comments
Route::middleware(['web', 'auth'])->post('/incidents/{incident}/comments', [CommentController::class, 'store'])->name('comments.store');

// Employee dashboard and routes
Route::middleware(['web', 'auth', \App\Http\Middleware\RoleMiddleware::class . ':employee'])->group(function () {
    Route::get('/dashboard/employee', function () {
        return view('dashboards.employee');
    })->name('dashboard.employee');
    Route::get('/dashboard/employee/incidents', [IncidentController::class, 'employeeIndex'])->name('employee.incidents.index');
    Route::get('/dashboard/employee/incidents/{incident}', [IncidentController::class, 'employeeShow'])->name('employee.incidents.show');
    Route::patch('/dashboard/employee/incidents/{incident}/status', [IncidentController::class, 'updateStatus'])->name('employee.incidents.updateStatus');
    Route::post('/dashboard/employee/incidents/{incident}/contact', [IncidentController::class, 'contactClient'])->name('employee.incidents.contactClient');
});

// Admin dashboard and routes
Route::middleware(['web', 'auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/dashboard/admin', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/admin/incidents', [App\Http\Controllers\AdminController::class, 'incidents'])->name('admin.incidents');
    Route::post('/dashboard/admin/incidents/{incident}/assign-team', [App\Http\Controllers\AdminController::class, 'assignTeam'])->name('admin.incidents.assignTeam');
    Route::get('/dashboard/admin/incidents/{incident}', [App\Http\Controllers\AdminController::class, 'show'])->name('admin.incidents.show');
    Route::delete('/dashboard/admin/incidents/{incident}', [App\Http\Controllers\AdminController::class, 'deleteIncident'])->name('admin.incidents.delete');
    Route::patch('/dashboard/admin/incidents/{incident}/status', [App\Http\Controllers\IncidentController::class, 'updateStatus'])->name('admin.incidents.updateStatus');
    Route::get('/dashboard/admin/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
    // Admin add entity routes (placeholders)
    Route::get('/dashboard/admin/projects', [\App\Http\Controllers\ProjetController::class, 'index'])->name('admin.add.project');
    Route::get('/dashboard/admin/projects/create', [\App\Http\Controllers\ProjetController::class, 'create'])->name('admin.create.project');
    Route::post('/dashboard/admin/projects', [\App\Http\Controllers\ProjetController::class, 'store'])->name('admin.store.project');
    Route::get('/dashboard/admin/clients', [\App\Http\Controllers\ClientController::class, 'index'])->name('admin.add.client');
    Route::get('/dashboard/admin/clients/create', [\App\Http\Controllers\ClientController::class, 'create'])->name('admin.create.client');
    Route::post('/dashboard/admin/clients', [\App\Http\Controllers\ClientController::class, 'store'])->name('admin.store.client');
    Route::get('/dashboard/admin/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('admin.add.employee');
    Route::get('/dashboard/admin/employees/create', [\App\Http\Controllers\EmployeeController::class, 'create'])->name('admin.create.employee');
    Route::post('/dashboard/admin/employees', [\App\Http\Controllers\EmployeeController::class, 'store'])->name('admin.store.employee');
    Route::get('/dashboard/admin/teams', [\App\Http\Controllers\TeamController::class, 'index'])->name('admin.add.team');
    Route::get('/dashboard/admin/teams/create', [\App\Http\Controllers\TeamController::class, 'create'])->name('admin.create.team');
    Route::post('/dashboard/admin/teams', [\App\Http\Controllers\TeamController::class, 'store'])->name('admin.store.team');
    Route::get('/dashboard/admin/slas', [\App\Http\Controllers\SlaController::class, 'index'])->name('admin.add.sla');
    Route::get('/dashboard/admin/slas/create', [\App\Http\Controllers\SlaController::class, 'create'])->name('admin.create.sla');
    Route::post('/dashboard/admin/slas', [\App\Http\Controllers\SlaController::class, 'store'])->name('admin.store.sla');
    Route::get('/admin/admins', [App\Http\Controllers\AdminController::class, 'adminIndex'])->name('admin.admins');
    Route::get('/admin/admins/add', [App\Http\Controllers\AdminController::class, 'adminCreate'])->name('admin.add.admin');
    Route::post('/admin/admins', [App\Http\Controllers\AdminController::class, 'adminStore'])->name('admin.store.admin');
    Route::get('/admin/admins/{id}/edit', [\App\Http\Controllers\AdminController::class, 'adminEdit'])->name('admin.edit.admin');
    Route::put('/admin/admins/{id}', [\App\Http\Controllers\AdminController::class, 'adminUpdate'])->name('admin.update.admin');
    Route::delete('/admin/admins/{id}', [\App\Http\Controllers\AdminController::class, 'adminDestroy'])->name('admin.delete.admin');
    Route::get('/admin/projects/{id}/edit', [App\Http\Controllers\ProjetController::class, 'edit'])->name('admin.edit.project');
    Route::post('/admin/projects/{id}/update', [App\Http\Controllers\ProjetController::class, 'update'])->name('admin.update.project');
    Route::delete('/admin/projects/{id}', [App\Http\Controllers\ProjetController::class, 'destroy'])->name('admin.delete.project');
    Route::get('/admin/projects', [App\Http\Controllers\ProjetController::class, 'index'])->name('admin.projects');
    Route::get('/admin/clients/{id}/edit', [\App\Http\Controllers\ClientController::class, 'edit'])->name('admin.edit.client');
    Route::put('/admin/clients/{id}', [\App\Http\Controllers\ClientController::class, 'update'])->name('admin.update.client');
    Route::delete('/admin/clients/{id}', [\App\Http\Controllers\ClientController::class, 'destroy'])->name('admin.delete.client');
    Route::get('/admin/clients/list', [\App\Http\Controllers\ClientController::class, 'index'])->name('admin.clients');
    Route::get('/admin/employees/{id}/edit', [\App\Http\Controllers\EmployeeController::class, 'edit'])->name('admin.edit.employee');
    Route::put('/admin/employees/{id}', [\App\Http\Controllers\EmployeeController::class, 'update'])->name('admin.update.employee');
    Route::delete('/admin/employees/{id}', [\App\Http\Controllers\EmployeeController::class, 'destroy'])->name('admin.delete.employee');
    Route::get('/admin/employees/list', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('admin.employees');
    Route::get('/admin/teams/{id}/edit', [\App\Http\Controllers\TeamController::class, 'edit'])->name('admin.edit.team');
    Route::put('/admin/teams/{id}', [\App\Http\Controllers\TeamController::class, 'update'])->name('admin.update.team');
    Route::delete('/admin/teams/{id}', [\App\Http\Controllers\TeamController::class, 'destroy'])->name('admin.delete.team');
    Route::get('/admin/teams/list', [\App\Http\Controllers\TeamController::class, 'index'])->name('admin.teams');
    
    // Export routes
    Route::get('/admin/export/{type}/{format}', [App\Http\Controllers\AdminController::class, 'exportData'])->name('admin.export');
});

// Authenticated users (clients, employees, admins) profile route
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Notification routes
    Route::get('/notifications/count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/delete', [\App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
});

// Logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');