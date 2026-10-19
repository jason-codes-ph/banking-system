<?php
namespace App\Observers;

use App\Models\Transaction;
use App\Models\Account;
use App\Notifications\LowBalanceNotification;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        $account = Account::where('id', $transaction->account_id)
                          ->lockForUpdate()
                          ->first();

        $newBalance = match ($transaction->type) {
            'deposit' => $account->balance + $transaction->amount,
            'withdrawal', 'transfer' => $account->balance - $transaction->amount,
        };

        // Critical Rule: bawal bumaba sa minimum o mag-negative
        if ($newBalance < 0 || $newBalance < $account->minimum_balance) {
            throw new \RuntimeException(
                "Insufficient funds. Projected balance: {$newBalance}, Minimum: {$account->minimum_balance}"
            );
        }

        $account->update(['balance' => $newBalance]);

        // Low Balance Notification
        if (in_array($transaction->type, ['withdrawal', 'transfer'])) {
            if ($newBalance <= $account->minimum_balance && is_null($account->low_balance_alerted_at)) {
                $account->user->notify(new LowBalanceNotification($account));
                $account->update(['low_balance_alerted_at' => now()]);
            }
        }

        // I-reset ang flag kapag tumaas na ulit
        if ($transaction->type === 'deposit' && $newBalance > $account->minimum_balance) {
            $account->update(['low_balance_alerted_at' => null]);
        }
    }
}