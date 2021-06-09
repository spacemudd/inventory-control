<?php

use Illuminate\Database\Seeder;

class CreateNewApproverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = \App\Models\Employee::firstOrCreate([
            "code" => 'X3',
            "department_id" => '1',
            "staff_type_id" => null,
            "name" => 'Engr. Waleed Al Shubeyan',
            "approver" => 1,
            "financial_auth" => null,
            "financial_auth_currency" => null,
            "designation" => "Deputy General Manager <br/> Head of Premises & Admin. Services Dept.",
        ]);

        $employee2 = \App\Models\Employee::firstOrCreate([
            "code" => 'X4',
            "department_id" => '1',
            "staff_type_id" => null,
            "name" => 'Engr. Haitham AbdulRahman Al Koblan',
            "approver" => 1,
            "financial_auth" => null,
            "financial_auth_currency" => null,
            "designation" => "Head of Premises Center",
        ]);
    }
}
