<?php

use App\Http\Controllers\ActivityCommentController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GeneralExpenseCategoryController;
use App\Http\Controllers\GeneralExpenseController;
use App\Http\Controllers\GeneralExpenseReportController;
use App\Http\Controllers\GeneralRevenueController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectActivityController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitsReportsController;
use App\Livewire\CreateUnitWizard;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/', [HomeController::class, 'root'])->name('home');

// #################################### Employees #############################################
Route::resource('employees', EmployeeController::class);
Route::post('/employees/{employee}/favourite', [EmployeeController::class, 'toggleFavourite'])->name('employees.favourite');

// #################################### Clients #############################################
Route::resource('clients', ClientController::class);
Route::post('/clients/{client}/favorite', [ClientController::class, 'toggleFavorite'])->name('clients.favorite');

// #################################### Projects #############################################
Route::resource('projects', ProjectController::class);
Route::post('projects/{project}/toggle-favourite', [ProjectController::class, 'toggleFavourite'])->name('projects.toggleFavourite');

// ✅ Project Members
Route::get('/projects/{project}/members/add', [ProjectMemberController::class, 'create'])->name('projects.members.add');
Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
// #################################### Project Files #############################################
Route::post('/projects/{project}/files', [ProjectController::class, 'uploadFiles'])->name('projects.files.upload');
Route::delete('projects/{project}/files/{fileIndex}', [ProjectController::class, 'deleteFile'])->name('projects.files.destroy');

// #################################### Tasks #############################################
Route::resource('tasks', TaskController::class);
Route::resource('projects.tasks', TaskController::class);
Route::delete('/tasks/bulk-delete', [TaskController::class, 'bulkDelete'])->name('tasks.bulkDelete');

// #################################### Expenses #############################################
Route::resource('projects.expenses', ExpenseController::class);
Route::put('projects/{project}/expenses/{expense}', [ExpenseController::class, 'update'])->name('projects.expenses.update');

// #################################### Activities & Comments #############################################
Route::resource('projects.activities', ProjectActivityController::class);
Route::post('activities/{activity}/comments', [ProjectActivityController::class, 'storeComment'])->name('activities.comments.store');
Route::put('comments/{comment}', [ActivityCommentController::class, 'update'])->name('comments.update');
Route::delete('comments/{comment}', [ActivityCommentController::class, 'destroy'])->name('comments.destroy');

// #################################### Attachments #############################################
Route::post('tasks/{task}/attachments', [AttachmentController::class, 'store'])->name('tasks.attachments.store');
Route::get('attachments/{id}/download', [AttachmentController::class, 'download'])->name('attachments.download');
Route::get('attachments/{id}/view', [AttachmentController::class, 'view'])->name('attachments.view');
Route::delete('attachments/{id}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

// #################################### Team #############################################
Route::get('/team', [TeamMemberController::class, 'index'])->name('team.index');
Route::post('/team-members/store', [TeamMemberController::class, 'store'])->name('team-members.store');
Route::get('/team-members/{id}/edit', [TeamMemberController::class, 'edit']);
Route::put('/team-members/{id}', [TeamMemberController::class, 'update']);
Route::get('/team-members/delete/{id}', [TeamMemberController::class, 'destroy']);
Route::post('/team-members/{id}/favourite', [TeamMemberController::class, 'toggleFavourite']);

// #################################### Profiles #############################################
Route::resource('profiles', ProfileController::class);
Route::post('/profiles/{profile}/favourite', [ProfileController::class, 'toggleFavourite'])->name('profiles.favourite');
Route::get('/profiles/create/{employee_id?}', [ProfileController::class, 'create'])->name('profiles.create.withEmployee');
Route::get('employees/{employee}/profile', [ProfileController::class, 'show'])->name('employees.profile.show');

// #################################### Experience #############################################
Route::post('/experiences/store', [App\Http\Controllers\ExperienceController::class, 'store'])->name('experiences.store');

// #################################### Salaries #############################################
Route::resource('salaries', SalaryController::class);
Route::get('/salaries/employee/{id}', [SalaryController::class, 'showForEmployee'])->name('salaries.employee');
Route::post('/salaries/generate', [SalaryController::class, 'generate'])->name('salaries.generate');

// #################################### Stock #############################################
Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
Route::put('/stock/{product}', [StockController::class, 'update'])->name('stock.update');
Route::delete('/stock/{product}', [StockController::class, 'destroy'])->name('stock.destroy');
Route::post('/stock/{product}/adjust', [StockController::class, 'adjustStock'])->name('stock.adjust');
Route::get('/stock/{product}/movements', [StockController::class, 'movements'])->name('stock.movements');

// #################################### Units Reports #############################################
Route::prefix('units/reports')->name('units.reports.')->group(function () {
    Route::get('/', [UnitsReportsController::class, 'index'])->name('index');
    Route::get('/real-estate', [UnitsReportsController::class, 'realEstatePage'])->name('realestate');
    Route::get('/real-estate/data', [UnitsReportsController::class, 'getRealEstateData'])->name('realestate.data');
    Route::get('/sales', [UnitsReportsController::class, 'getSalesData'])->name('sales');
    Route::get('/kpis', [UnitsReportsController::class, 'getKPIs'])->name('kpis');
    Route::get('/construction', [UnitsReportsController::class, 'constructionPage'])->name('construction');
    Route::get('/construction/data', [UnitsReportsController::class, 'getConstructionData'])->name('construction.data');
    Route::get('/finance', [UnitsReportsController::class, 'financePage'])->name('finance');
    Route::get('/finance/data', [UnitsReportsController::class, 'getFinanceData'])->name('finance.data');
});

// #################################### Units #############################################
Route::get('/units', [UnitController::class, 'index'])->name('units.index');
Route::get('/units/create', CreateUnitWizard::class)->name('units.create');
Route::get('/units/{unitId}/edit', CreateUnitWizard::class)->name('units.edit');
Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show');
Route::patch('/units/{id}/restore', [UnitController::class, 'restore'])->name('units.restore');
Route::delete('/units/{id}/force-delete', [UnitController::class, 'forceDelete'])->name('units.forceDelete');
Route::patch('/units/{unit}/toggle-favorite', [UnitController::class, 'toggleFavorite'])->name('units.toggleFavorite');

// #################################### Invoices #############################################
Route::prefix('units/{unitId}/invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('{invoiceId}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('{invoiceId}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('{invoiceId}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});

// #################################### Calendar #############################################
Route::get('/calendar', [EventController::class, 'index'])->name('calendar.index');
Route::get('/calendar/events', [EventController::class, 'allEvents'])->name('calendar.events');
Route::post('/calendar', [EventController::class, 'store'])->name('calendar.store');
Route::put('/calendar/{event}', [EventController::class, 'update'])->name('calendar.update');
Route::delete('/calendar/{event}', [EventController::class, 'destroy'])->name('calendar.destroy');

// #################################### Expenses #############################################
Route::resource('general-expenses', GeneralExpenseController::class);

Route::delete(
    'general-expenses/{generalExpense}',
    [GeneralExpenseController::class, 'destroy']
)->name('general-expenses.destroy');

// Route لإحضار بيانات الـ Edit
Route::get('general-expenses/{generalExpense}/edit', [GeneralExpenseController::class, 'edit']);

// expenses categories
Route::resource('general-expense-categories', GeneralExpenseCategoryController::class)
    ->except(['show']);

Route::get('general-expense-report', [GeneralExpenseReportController::class, 'index'])
    ->name('general-expense-report.index');

// //////////////////////////// General Revenue //////////////////////
Route::resource('general-revenues', GeneralRevenueController::class);
