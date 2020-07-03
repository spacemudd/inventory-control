<?php

use Illuminate\Database\Migrations\Migration;

class AddContractPermissions extends Migration
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
            'name' => 'view-contracts',
            'guard_name' => 'web',
            'type' => 'system',
        ]);

        Permission::create([
            'name' => 'create-contracts',
            'guard_name' => 'web',
            'type' => 'system',
        ]);

        Permission::create([
            'name' => 'edit-contracts',
            'guard_name' => 'web',
            'type' => 'system',
        ]);

        Permission::create([
            'name' => 'delete-contracts',
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
        optional(Permission::findByName('view-contracts'))->delete();
        optional(Permission::findByName('create-contracts'))->delete();
        optional(Permission::findByName('edit-contracts'))->delete();
        optional(Permission::findByName('delete-contracts'))->delete();

        cache()->clear();
    }
}
