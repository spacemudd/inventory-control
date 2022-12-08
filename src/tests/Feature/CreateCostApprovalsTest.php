<?php

namespace Tests\Feature;

use App\CostApproval;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCostApprovalsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_attaching_multiple_quotations_to_cost_approval()
    {
        $user = factory(User::class)->create();

        $employee = factory(Employee::class)->create();
        $costCenter = factory(Department::class)->create();
        $supplier = factory(Vendor::class)->create();

        $url = route('cost-approvals.store');

        $response = $this->actingAs($user)->post($url, [
            'requested_by_id' => $employee->id,
            'cost_center_id' => $costCenter->id,
            'vendor_id' => $supplier->id,
            'quotation_numbers' => ['ByeBye', 'HelloHello'],
            'date' => now()->toDateString(),
            'purpose_of_request' => 'This is a test',
            'due_diligence_approved' => false,
        ]);

        $costApproval = CostApproval::first();

        $this->assertEquals(2, $costApproval->adhoc_quotations()->count());
    }
}
