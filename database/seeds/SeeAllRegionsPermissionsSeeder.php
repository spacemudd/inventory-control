<?php

use Illuminate\Database\Seeder;

class SeeAllRegionsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'see-all-region-material-requests',
            'see-all-region-quotations',
            'see-all-region-job-orders',
        ];

        foreach($permissions as $permission) {
            \App\Models\Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
                'type' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
