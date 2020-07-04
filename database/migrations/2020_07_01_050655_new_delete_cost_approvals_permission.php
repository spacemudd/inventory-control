<?php

use App\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewDeleteCostApprovalsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws \Exception
     */
    public function up()
    {
        Permission::create([
            'name' => 'delete-cost-approvals',
            'guard_name' => 'web',
            'type' => 'system',
        ]);

        cache()->clear();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws \Exception
     */
    public function down()
    {
        $perm = Permission::findByName('delete-cost-approvals');

        if ($perm) {
            $perm->delete();
        }

        cache()->clear();
    }
}
