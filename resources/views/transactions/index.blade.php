<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions — Banking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
            padding: 30px 15px;
        }
        .container {
            max-width: 900px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 20px 25px;
            border: none;
        }
        .card-header h4 {
            margin: 0;
            font-weight: 700;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px 20px;
        }
        .btn-outline-secondary {
            border-radius: 8px;
        }
        table {
            margin-bottom: 0;
        }
        th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            border: none;
        }
        td {
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        .badge-deposit {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-withdrawal {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .alert-success {
            border-radius: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>📋 Transaction History</h4>
                <a href="{{ route('transactions.create') }}" class="btn btn-light btn-sm">+ New Transaction</a>
            </div>

            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success m-3">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Account No.</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $tx)
                                <tr>
                                    <td>{{ $tx->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $tx->account->account_number ?? '—' }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $tx->type === 'deposit' ? 'badge-deposit' : 'badge-withdrawal' }}">
                                            {{ ucfirst($tx->type) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold {{ $tx->type === 'deposit' ? 'text-success' : 'text-danger' }}">
                                        {{ $tx->type === 'deposit' ? '+' : '-' }}₱{{ number_format($tx->amount, 2) }}
                                    </td>
                                    <td class="text-muted">{{ $tx->notes ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <h5 class="mb-2">No transactions yet</h5>
                        <p class="mb-4">Start by recording your first transaction.</p>
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary">+ Record Transaction</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>