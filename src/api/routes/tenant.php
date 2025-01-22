<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenant\AuthenticationController;
use App\Http\Controllers\Api\Tenant\CashRegisterController;
use App\Http\Controllers\Api\Tenant\CategoryController;
use App\Http\Controllers\Api\Tenant\CustomerController;
use App\Http\Controllers\Api\Tenant\FeeController;
use App\Http\Controllers\Api\Tenant\InsurerController;
use App\Http\Controllers\Api\Tenant\IntermediaryController;
use App\Http\Controllers\Api\Tenant\InventoryController;
use App\Http\Controllers\Api\Tenant\LabelCodeController;
use App\Http\Controllers\Api\Tenant\OrderController;
use App\Http\Controllers\Api\Tenant\PatientController;
use App\Http\Controllers\Api\Tenant\PrescriberController;
use App\Http\Controllers\Api\Tenant\ProductController;
use App\Http\Controllers\Api\Tenant\RoleController;
use App\Http\Controllers\Api\Tenant\SettingController;
use App\Http\Controllers\Api\Tenant\ShelfController;
use App\Http\Controllers\Api\Tenant\SupplierController;
use App\Http\Controllers\Api\Tenant\TaxController;
use App\Http\Controllers\Api\Tenant\UserController;
use Illuminate\Support\Facades\Route;
//use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

//use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::group([
    'prefix' => '/{tenant}',
    'middleware' => [InitializeTenancyByPath::class],
], function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
    });

    //authentication
    Route::post('/v1/login', [AuthenticationController::class, 'login']);

    Route::group([
        'prefix' => 'v1',
        'middleware' => 'auth:sanctum',
    ], function () {
        //logout
        Route::post('logout', [AuthenticationController::class, 'logout']);

        //users rest crud
        Route::get('users/{userId}', [UserController::class, 'show']);
        Route::get('users', [UserController::class, 'getUsers']);
        Route::post('users', [UserController::class, 'createUser']);
        Route::put('users/{userId}', [UserController::class, 'updateUser']);
        Route::delete('users/{userId}', [UserController::class, 'delete']);

        //roles rest crud
        Route::get('roles/{roleId}', [RoleController::class, 'show']);
        Route::get('roles', [RoleController::class, 'getRoles']);
        Route::post('roles', [RoleController::class, 'createRole']);
        Route::put('roles/{roleId}', [RoleController::class, 'updateRole']);
        Route::delete('roles/{roleId}', [RoleController::class, 'delete']);
        Route::get('permissions', [RoleController::class, 'getPermissions']);
        Route::post('assign', [RoleController::class, 'assign']);

        //shelves rest crud
        Route::get('shelves/{shelfId}', [ShelfController::class, 'show']);
        Route::get('shelves', [ShelfController::class, 'getShelves']);
        Route::post('shelves', [ShelfController::class, 'createShelf']);
        Route::put('shelves/{shelfId}', [ShelfController::class, 'updateShelf']);
        Route::delete('shelves/{shelfId}', [ShelfController::class, 'delete']);

        //Categories rest crud
        Route::get('categories/{categoryId}', [CategoryController::class, 'show']);
        Route::get('categories', [CategoryController::class, 'getCategories']);
        Route::post('categories', [CategoryController::class, 'createCategory']);
        Route::put('categories/{categoryId}', [CategoryController::class, 'updateCategory']);
        Route::delete('categories/{categoryId}', [CategoryController::class, 'delete']);

        //Taxes rest crud
        Route::get('taxes/{taxId}', [TaxController::class, 'getTax']);
        Route::get('taxes', [TaxController::class, 'getTaxes']);
        Route::post('taxes', [TaxController::class, 'createTax']);
        Route::put('taxes/{taxId}', [TaxController::class, 'updateTax']);
        Route::delete('taxes/{taxId}', [TaxController::class, 'deleteTax']);

        //Insurance rest crud
        Route::get('insurers/{insurerId}', [InsurerController::class, 'getInsurer']);
        Route::get('insurers', [InsurerController::class, 'getInsurers']);
        Route::post('insurers', [InsurerController::class, 'createInsurer']);
        Route::put('insurers/{insurerId}', [InsurerController::class, 'updateInsurer']);
        Route::delete('insurers/{insurerId}', [InsurerController::class, 'deleteInsurer']);

        //Customers rest crud
        Route::get('customers/{customerId}', [CustomerController::class, 'show']);
        Route::get('customers', [CustomerController::class, 'getCustomers']);
        Route::post('customers', [CustomerController::class, 'createCustomer']);
        Route::put('customers/{customerId}', [CustomerController::class, 'updateCustomer']);
        Route::delete('customers/{customerId}', [CustomerController::class, 'delete']);

        //Fees rest crud
        Route::get('fees/{feeId}', [FeeController::class, 'show']);
        Route::get('fees', [FeeController::class, 'getFees']);
        Route::post('fees', [FeeController::class, 'createFee']);
        Route::put('fees/{feeId}', [FeeController::class, 'updateFee']);
        Route::delete('fees/{feeId}', [FeeController::class, 'delete']);

        //Supplier rest crud
        Route::get('suppliers/{supplierId}', [SupplierController::class, 'show']);
        Route::get('suppliers', [SupplierController::class, 'getSuppliers']);
        Route::post('suppliers', [SupplierController::class, 'createSupplier']);
        Route::put('suppliers/{supplierId}', [SupplierController::class, 'updateSupplier']);
        Route::delete('suppliers/{supplierId}', [SupplierController::class, 'delete']);

        //Product
        Route::get('products/{productId}', [ProductController::class, 'show']);
        Route::get('products', [ProductController::class, 'getProducts']);
        Route::post('products', [ProductController::class, 'createProduct']);
        Route::put('products/{productId}', [ProductController::class, 'updateProduct']);
        Route::delete('products/{productId}', [ProductController::class, 'delete']);

        //Inventory
        Route::get('inventories/{inventoryId}', [InventoryController::class, 'getInventory']);
        Route::get('inventories', [InventoryController::class, 'getInventories']);
        Route::post('inventories', [InventoryController::class, 'createInventory']);
        Route::delete('inventories/{inventoryId}', [InventoryController::class, 'deleteInventory']);

        //Cash register
        Route::get('cashregisters', [CashRegisterController::class, 'getCashRegisters']);
        Route::post('cashregisters', [CashRegisterController::class, 'openCashRegister']);
        Route::put('cashregisters/{cashregisterId}', [CashRegisterController::class, 'closeCashRegister']);

        //Prescriber
        Route::get('prescribers/{prescriberId}', [PrescriberController::class, 'show']);
        Route::get('prescribers', [PrescriberController::class, 'getPrescribers']);
        Route::post('prescribers', [PrescriberController::class, 'createPrescriber']);
        Route::put('prescribers/{prescriberId}', [PrescriberController::class, 'updatePrescriber']);
        Route::delete('prescribers/{prescriberId}', [PrescriberController::class, 'delete']);

        //LabelCode
        Route::get('labels/{labelcodeId}', [LabelCodeController::class, 'show']);
        Route::get('labels', [LabelCodeController::class, 'getLabelCodes']);
        Route::post('labels', [LabelCodeController::class, 'createLabelCode']);
        Route::put('labels/{labelcodeId}', [LabelCodeController::class, 'updateLabelCode']);
        Route::delete('labels/{labelcodeId}', [LabelCodeController::class, 'delete']);

        // patients
        Route::get('patients/{patientId}', [PatientController::class, 'show']);
        Route::get('patients', [PatientController::class, 'getPatients']);
        Route::post('patients', [PatientController::class, 'createPatient']);
        Route::put('patients/{patientId}', [PatientController::class, 'updatePatient']);
        Route::delete('patients/{patientId}', [PatientController::class, 'delete']);

        // intermediaries
        Route::get('intermediaries/{intermediaryId}', [IntermediaryController::class, 'show']);
        Route::get('intermediaries', [IntermediaryController::class, 'getIntermediaries']);
        Route::post('intermediaries', [IntermediaryController::class, 'createIntermediary']);
        Route::put('intermediaries/{intermediaryId}', [IntermediaryController::class, 'updateIntermediary']);
        Route::delete('intermediaries/{intermediaryId}', [IntermediaryController::class, 'delete']);

        // orders
        Route::get('orders/{orderId}', [OrderController::class, 'getOrder']);
        Route::get('orders', [OrderController::class, 'getOrders']);
        Route::post('orders', [OrderController::class, 'createOrder']);
        Route::put('orders/{orderId}', [OrderController::class, 'updateOrder']);
        Route::delete('orders/{orderId}', [OrderController::class, 'deleteOrder']);

        // settings
        Route::get('settings', [SettingController::class, 'getSettings']);
        Route::put('settings', [SettingController::class, 'updateSetting']);
    });
});
