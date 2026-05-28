<?php

namespace Tests\Feature;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_create_quotation(): void
    {
        $response = $this->postJson('/quotation', [
            'age' => '28,35',
            'currency_id' => 'EUR',
            'start_date' => '2020-10-01',
            'end_date' => '2020-10-30',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_authenticated_user_can_create_quotation(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '28,35',
                'currency_id' => 'EUR',
                'start_date' => '2020-10-01',
                'end_date' => '2020-10-30',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'total' => 117,
                'currency_id' => 'EUR',
                'quotation_id' => 1,
            ]);
    }

    public function test_valid_quotation_request_is_persisted(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '28,35',
                'currency_id' => 'EUR',
                'start_date' => '2020-10-01',
                'end_date' => '2020-10-30',
            ])
            ->assertOk();

        $this->assertDatabaseHas('quotations', [
            'user_id' => $user->id,
            'currency_id' => 'EUR',
            'trip_length' => 30,
            'total' => 117.00,
        ]);

        $quotation = Quotation::firstOrFail();

        $this->assertSame('2020-10-01', $quotation->start_date->toDateString());
        $this->assertSame('2020-10-30', $quotation->end_date->toDateString());
        $this->assertSame([28, 35], $quotation->ages);
    }

    public function test_invalid_age_list_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '28,,35',
                'currency_id' => 'EUR',
                'start_date' => '2020-10-01',
                'end_date' => '2020-10-30',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['age']);
    }

    public function test_age_below_supported_range_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '17',
                'currency_id' => 'EUR',
                'start_date' => '2020-10-01',
                'end_date' => '2020-10-30',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['age']);
    }

    public function test_age_above_supported_range_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '71',
                'currency_id' => 'EUR',
                'start_date' => '2020-10-01',
                'end_date' => '2020-10-30',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['age']);
    }

    public function test_invalid_currency_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '28,35',
                'currency_id' => 'JPY',
                'start_date' => '2020-10-01',
                'end_date' => '2020-10-30',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['currency_id']);
    }

    public function test_end_date_before_start_date_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/quotation', [
                'age' => '28,35',
                'currency_id' => 'EUR',
                'start_date' => '2020-10-30',
                'end_date' => '2020-10-01',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['end_date']);
    }
}
