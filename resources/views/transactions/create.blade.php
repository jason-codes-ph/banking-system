<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Transaction — Banking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
            padding: 30px 15px;
        }
        .container {
            max-width: 500px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 20px 25px;
            border: none;
            border-radius: 16px 16px 0 0 !important;
        }
        .card-header h4 {
            margin: 0;
            font-weight: 700;
        }
        .form-label {
            font-weight: 500;
            color: #475569;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #cbd5e1;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
        }
        .btn-outline-secondary {
            border-radius: 8px;
        }
        .alert-danger {
            border-radius: 8px;
        }
        .nav-links {
            margin-bottom: 25px;
            text-align: center;
        }
        .nav-links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-weight: 500;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .balance-info {
            background: #eff6ff;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">🏦 Dashboard</a>
            <a href="{{ route('transactions.create') }}">➕ New Transaction</a>
            <a href="{{ route('transactions.index') }}">📋 All Transactions</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>➕ New Transaction</h4>
            </div>

            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="account_id" class="form-label">Account</label>
                        <select name="account_id" id="account_id" class="form-select" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->account_number }} — Balance: ₱{{ number_format($acc->balance, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Transaction Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="deposit" {{ old('type') === 'deposit' ? 'selected' : '' }}>💵 Deposit</option>
                            <option value="withdrawal" {{ old('type') === 'withdrawal' ? 'selected' : '' }}>💳 Withdrawal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₱)</label>
                        <input type="number" step="0.01" min="1" name="amount" id="amount" 
                               class="form-control" value="{{ old('amount') }}" required placeholder="0.00">
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Add a note...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Submit Transaction</button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">← Back to List</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>