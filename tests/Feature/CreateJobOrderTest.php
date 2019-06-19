<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Location;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateJobOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_store_new_job_order()
    {
        $user = factory(User::class)->create();

        $data = [
            'date' => now()->toDateTimeString(),
            'location_id' => factory(Location::class)->create()->id,
            'requested_through_type' => $this->faker->randomElement(['email', 'phone']),
            'job_description' => $this->faker->text,
            'employee_id' => factory(Employee::class)->create()->id, // requester id.
            'time_start' => now()->toDateTimeString(),
            'technicians' => [
                [
                    'addEmployees' => factory(Employee::class)->create(),
                    'time_start' => now()->toDateTimeString(),
                    'time_end' => null,
                ],
            ]
        ];

        $this->actingAs($user)
            ->post(route('job-orders.store'), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('job_orders', [
            'employee_id' => $data['employee_id'],
            'job_description' => $data['job_description'],
        ]);
    }

    public function test_storing_job_order_with_materials_affects_stock()
    {
        $user = factory(User::class)->create();

        $stock = factory(Stock::class)->create();
        factory(StockMovement::class)->create([
            'stock_id' => $stock->id,
            'in' => 100,
            'out' => 0,
        ]);

        $data = [
            'date' => now()->toDateTimeString(),
            'location_id' => factory(Location::class)->create()->id,
            'requested_through_type' => $this->faker->randomElement(['email', 'phone']),
            'job_description' => $this->faker->text,
            'employee_id' => factory(Employee::class)->create()->id, // requester id.
            'time_start' => now()->toDateTimeString(),
            'technicians' => [
                [
                    'addEmployees' => factory(Employee::class)->create(),
                    'time_start' => now()->toDateTimeString(),
                    'time_end' => null,
                ],
            ],
            'materials' => [
                [
                    'stock_id' => $stock->id,
                    'quantity' => 20,
                ],
            ],
        ];

        $this->actingAs($user)
            ->post(route('job-orders.store'), $data)
            ->assertRedirect();

        $this->assertEquals(80, $stock->on_hand_quantity);
    }
}
