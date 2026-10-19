<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $account = Account::first();
        $transactions = Transaction::latest()->limit(10)->get();
        $deposits = Transaction::where('type', 'deposit')->sum('amount');
        $withdrawals = Transaction::where('type', 'withdrawal')->sum('amount');
        
        return view('dashboard.index', compact(
            'account',
            'transactions',
            'deposits',
            'withdrawals'
        ));
    }
}