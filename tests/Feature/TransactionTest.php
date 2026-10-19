<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use App\Notifications\LowBalanceNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_withdrawal_at_minimum_balance_records_transaction_and_notifies_user(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'account_number' => 'TEST-1001',
            'balance' => 150.00,
            'minimum_balance' => 100.00,
        ]);

        $response = $this->post(route('transactions.store'), [
            'account_id' => $account->id,
            'type' => 'withdrawal',
            'amount' => 50.00,
        ]);

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'type' => 'withdrawal',
            'amount' => 50.00,
        ]);
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'balance' => 100.00,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => LowBalanceNotification::class,
        ]);
    }
}
