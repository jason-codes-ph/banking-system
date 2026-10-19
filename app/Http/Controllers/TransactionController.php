<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'type'       => 'required|in:deposit,withdrawal',
            'amount'     => 'required|numeric|min:1',
            'notes'      => 'nullable|string',
        ]);

        $validator->after(function ($v) use ($request) {
            if ($request->type !== 'withdrawal') return;
            $account = Account::find($request->account_id);
            if (!$account) return;
            $projected = $account->balance - $request->amount;
            if ($projected < 0 || $projected < $account->minimum_balance) {
                $v->errors()->add('amount', 
                    "Insufficient funds. Available above minimum: " . 
                    ($account->balance - $account->minimum_balance)
                );
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request) {
            Transaction::create($request->only(['account_id', 'type', 'amount', 'notes']));
        });

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded.');
        
    }
}