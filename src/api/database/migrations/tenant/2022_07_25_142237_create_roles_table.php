<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('label', 50);
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained('permissions');
            $table->foreignId('role_id')->constrained('roles');
            $table->timestamps();
            $table->unique(['permission_id', 'role_id']);
        });

        DB::table('permissions')->insert([
            ['code' => "ADDUSER", 'label' => "Can Add a User"],
            ['code' => "SHOWUSER", 'label' => "View Users"],
            ['code' => "UPDATEUSER", 'label' => "Update a User"],
            ['code' => "DELETEUSER", 'label' => "Delete User"],
            ['code' => "SHOWLOG", 'label' => "View Logs"],
            ['code' => "ADDTAX", 'label' => "Add Tax"],
            ['code' => "SHOWTAX", 'label' => "View Taxes"],
            ['code' => "UPDATETAX", 'label' => "Update Tax"],
            ['code' => "DELELETAX", 'label' => "Delete Tax"],
            ['code' => "IMPORT", 'label' => "Can Import"],
            ['code' => "SHOWSETTINGS", 'label' => "View Settings"],
            ['code' => "BACKUPDB", 'label' => "Download Backup"],
            ['code' => "ADDROLE", 'label' => "Create Role"],
            ['code' => "SHOWROLE", 'label' => "View Roles"],
            ['code' => "UPDATEROLE", 'label' => "Update a Role"],
            ['code' => "DELETEROLE", 'label' => "Delete Role"],
            ['code' => "ASSIGNROLE", 'label' => "Assign role"],
            ['code' => "VIEWADMINMODULE", 'label' => "View Administration Module"],
            ['code' => "ADDPRODUCT", 'label' => "Can Add a Product"],
            ['code' => "SHOWPRODUCT", 'label' => "View Products"],
            ['code' => "UPDATEPRODUCT", 'label' => "Update a Product"],
            ['code' => "DELETEPRODUCT", 'label' => "Delete Product"],
            ['code' => "ADDCATEGORY", 'label' => "Add a Category"],
            ['code' => "SHOWCATEGORY", 'label' => "View Categories"],
            ['code' => "UPDATECATEGORY", 'label' => "Update a category"],
            ['code' => "DELETECATEGORY", 'label' => "Delete a Category"],
            ['code' => "ADDSHELF", 'label' => "Add a Shelf"],
            ['code' => "SHOWSHELF", 'label' => "View Shelves"],
            ['code' => "UPDATESHELF", 'label' => "Update a shelf"],
            ['code' => "DELETESHELF", 'label' => "Delete a shelf"],
            ['code' => "DECOMPOSEPRODUCT", 'label' => "Decompose a Product"],
            ['code' => "SHOWFEE", 'label' => "View Fees"],
            ['code' => "ADDFEE", 'label' => "Add a Fee"],
            ['code' => "UPDATEFEE", 'label' => "Update a fee"],
            ['code' => "DELETEFEE", 'label' => "Delete a Fee"],
            ['code' => "SHOWLABELCODE", 'label' => "Show Label Codes"],
            ['code' => "ADDLABELCODE", 'label' => "Add Label Code"],
            ['code' => "UPDATELABELCODE", 'label '=> "Update Label Code"],
            ['code' => "DELETELABELCODE", 'label' => "Delete a Label Code"],
            ['code' => "VIEWPRODUCTMODULE", 'label' => "View Product Module"],
            ['code' => "CANPRINT", 'label' => "Can print document"],
            ['code' => "CORRECTSTOCK", 'label' => "Correct Stock"],
            ['code' => "BULKCORRECTSTOCK", 'label' => "Bulk stock correction"],
            ['code' => "SHOWINVENTORY", 'label' => "View Inventory Items"],
            ['code' => "ADDINVIN", 'label' => "Create Inventory In"],
            ['code' => "ADDINVOUT", 'label' => "Take Inventory Out"],
            ['code' => "ADDSUPPLIER", 'label' => "Add Supplier"],
            ['code' => "SHOWSUPPLIER", 'label' => "View Suppliers"],
            ['code' => "UPDATESUPPLIER", 'label' => "Update Supplier"],
            ['code' => "DELETESUPPLIER", 'label' => "Delete Supplier"],
            ['code' => "ADDORDER", 'label' => "Add an Order"],
            ['code' => "SHOWORDER", 'label' => "View Order"],
            ['code' => "UPDATEORDER", 'label' => "Update Order"],
            ['code' => "DELETEORDER", 'label' => "Delete Order"],
            ['code' => "SHOWSTOCK", 'label' => "View Stock"],
            ['code' => "UPDATEINVENTORYITEM", 'label' => "Edit Inventory Item"],
            ['code' => "VIEWSTOCKMGMT", 'label' => "View Stock Management Module"],
            ['code' => "ADDPRESCRIPTION", 'label' => "Add Prescription"],
            ['code' => "SHOWPRESCRIPTION", 'label' => "View Prescriptions"],
            ['code' => "UPDATEPRESCRIPTION", 'label' => "Update Prescription"],
            ['code' => "DELETEPRESCRIPTION", 'label' => "Delete Prescription"],
            ['code' => "SETDISCOUNT", 'label' => "Set Discount"],
            ['code' => "ENTERCOVERAGE", 'label' => "Enter Coverage"],
            ['code' => "ADDPATIENT", 'label' => "Add Patient"],
            ['code' => "SHOWPATIENT", 'label' => "View Patients"],
            ['code' => "UPDATEPATIENT", 'label' => "Update Patient"],
            ['code' => "DELETEPATIENT", 'label' => "Delete Patient"],
            ['code' => "ADDDOCTOR", 'label' => "Add Doctor"],
            ['code' => "SHOWDOCTOR", 'label' => "View Doctor"],
            ['code' => "UPDATEDOCTOR", 'label' => "Update Doctor"],
            ['code' => "DELETEDOCTOR", 'label' => "Delete Doctor"],
            ['code' => "SEARCHNDC", 'label' => "Search NDC"],
            ['code' => "VIEWPRESCRIPTIONMODULE", 'label' => "View Prescription Module"],
            ['code' => "ADDSALE", 'label' => "Add a Sale"],
            ['code' => "SHOWSALES", 'label' => "View Sales"],
            ['code' => "UPDATESALE", 'label' => "Update Sale"],
            ['code' => "FORCESALE", 'label' => "Force Sale"],
            ['code' => "DELETESALE", 'label' => "Delete Sale"],
            ['code' => "REFUNDSALE", 'label' => "Refund Sale"],
            ['code' => "CREDITSALE", 'label' => "Sell on credit"],
            ['code' => "SHOWCASHREGISTER", 'label' => "View Cash Registers"],
            ['code' => "SWITCHCASHREGISTER", 'label' => "Open/Close cash Register"],
            ['code' => "ADDCUSTOMER", 'label' => "Add a Customer"],
            ['code' => "SHOWCUSTOMER", 'label' => "View Customers"],
            ['code' => "UPDATECUSTOMER", 'label' => "Update Customer"],
            ['code' => "DELETECUSTOMER", 'label' => "Delete Customer"],
            ['code' => "SHOWCUSTOMERPAY", 'label' => "View customer Payment Transactions"],
            ['code' => "RECEIVECUSTOMERPAY", 'label' => "Receive Customer Payment"],
            ['code' => "VIEWPOSMODULE", 'label' => "View POS Module"],
            ['code' => "SHOWINSRECONCILIATION", 'label' => "View Insurance Reconciliation"],
            ['code' => "ADDINSURER", 'label' => "Add Insurer"],
            ['code' => "SHOWINSURER", 'label' => "View Insurers"],
            ['code' => "UPDATEINSURER", 'label' => "Update Insurer"],
            ['code' => "DELETEINSURER", 'label' => "Delete Insurer"],
            ['code' => "SHOWINSPAY", 'label' => "View Insurer Payment Transactions"],
            ['code' => "RECVINSPAY", 'label' => "Receive Insurance Payment"],
            ['code' => "VIEWINSURANCEMODULE", 'label' => "View Insurer Module"],
            ['code' => "ADDINTERMEDIARY", 'label' => "Add Intermediary"],
            ['code' => "SHOWINTERMEDIARY", 'label' => "View Intermediaries"],
            ['code' => "UPDATEINTERMEDIARY", 'label' => "Update Intermediary"],
            ['code' => "DELETEINTERMEDIARY", 'label' => "Delete Intermediary"],
            ['code' => "SHOWREPORTTMODULE", 'label' => "View Reports Module"],
            ['code' => "SHOWSALESREPORT", 'label' => "View Sales Report"],
            ['code' => "SHOWSTOCKREPORT", 'label' => "View Stock Report"],
            ['code' => "SHOWCASHREGREPORT", 'label' => "View Cash Register Report"],
            ['code' => "SHOWGCTREPORT", 'label' => "View GCT Report"],
            ['code' => "EXPIREDPRODREPORT", 'label' => "View Expired Products Report"],
            ['code' => "CASHIERREPORT", 'label' => "View Cashier Report"],
            ['code' => "RETURNPRODUCTREPORT", 'label' => "View return Products Report"],
            ['code' => "SHOWADMINDASHBOARD", 'label' => "View Admin Dashboard"],
            ['code' => "CHANGESALESPRICE", 'label' => "Change Product Selling Price"],
            ['code' => "CHANGEPRESCPRICE", 'label' => "Change Prescribed Product Selling Price"]
        ]);

        DB::table('roles')->insert([
            [   
                'id' => 1, 
                'label' => 'Administrator', 
                'description' => 'The user who has access to all features',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $permissions = DB::table('permissions')->pluck('id');
        $roleId = 1;

        $rolePermissions = $permissions->map(function ($permissionId) use ($roleId) {
            return [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('role_permissions')->insert($rolePermissions);
    }

    /**"
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_permissions');
    }
};
