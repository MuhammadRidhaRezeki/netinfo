<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NetworkNodeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'packages' => \App\Models\Package::where('is_active', true)->orderBy('speed_mbps')->get(),
    ]);
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
});

Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin,technician,customer'])->group(function () {
    Route::get('/search', SearchController::class)->name('search');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/customers', [AdminController::class, 'customersIndex'])->name('customers.index');
    Route::post('/customers', [AdminController::class, 'customersStore'])->name('customers.store');
    Route::put('/customers/{customer}', [AdminController::class, 'customersUpdate'])->name('customers.update');
    Route::patch('/customers/{customer}/status', [AdminController::class, 'customerChangeStatus'])->name('customers.status');
    Route::delete('/customers/{customer}', [AdminController::class, 'customersDestroy'])->name('customers.destroy');

    Route::get('/network-nodes', [NetworkNodeController::class, 'index'])->name('network-nodes.index');

    Route::get('/tickets', [TicketController::class, 'adminIndex'])->name('tickets.index');
    Route::get('/tickets/export', [TicketController::class, 'export'])->name('tickets.export');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');

    Route::get('/billing', [InvoiceController::class, 'adminIndex'])->name('billing.index');
    Route::post('/invoices/generate', [InvoiceController::class, 'generateMonthly'])->name('invoices.generate');
    Route::get('/invoices/export', [InvoiceController::class, 'exportCsv'])->name('invoices.export');
    Route::patch('/invoices/{invoice}/verify', [InvoiceController::class, 'verify'])->name('invoices.verify');
});

Route::middleware(['auth', 'role:technician'])->prefix('technician')->name('technician.')->group(function () {
    Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('dashboard');
    Route::get('/customers', [TechnicianController::class, 'customersIndex'])->name('customers.index');
    Route::get('/network-nodes', [NetworkNodeController::class, 'index'])->name('network-nodes.index');
    Route::get('/tickets', [TicketController::class, 'technicianIndex'])->name('tickets.index');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
});

Route::middleware(['auth', 'role:admin,technician'])->group(function () {
    Route::post('/network-nodes', [NetworkNodeController::class, 'store'])->name('network-nodes.store');
    Route::put('/network-nodes/{node}', [NetworkNodeController::class, 'update'])->name('network-nodes.update');
    Route::delete('/network-nodes/{node}', [NetworkNodeController::class, 'destroy'])->name('network-nodes.destroy');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/note', [TicketController::class, 'addNote'])->name('tickets.note');
});

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/helpcare', [CustomerController::class, 'helpcare'])->name('helpcare');
    Route::post('/helpcare', [CustomerController::class, 'storeTicket'])->name('helpcare.store');
    Route::post('/invoices/{invoice}/proof', [CustomerController::class, 'uploadProof'])->name('invoices.proof.upload');
});

Route::middleware(['auth', 'role:admin,customer'])->group(function () {
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'printInvoice'])->name('invoices.print');
});

Route::middleware(['auth', 'role:customer,admin'])->get('/invoices/{invoice}/proof', [CustomerController::class, 'proofFile'])
    ->name('invoices.proof.download');
