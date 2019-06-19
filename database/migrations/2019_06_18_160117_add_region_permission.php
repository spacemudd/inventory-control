<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddRegionPermission extends Migration
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
            'name' => 'view-all-regions',
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
        $perm = Permission::findByName('view-all-regions');

        if ($perm) {
            $perm->delete();
        }

        cache()->clear();
    }
}
