<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Dashboard</title>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
            padding: 20px;
            color: #1f2937;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        header h1 { font-size: 2rem; margin-bottom: 10px; }
        .nav-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .nav-bar a {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-bar a:hover { background: rgba(255,255,255,0.4); }
        .alert-box {
            background: #fef2f2;
            border-left: 5px solid #ef4444;
            color: #b91c1c;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: none;
            font-weight: 500;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover { transform: translateY(-5px); }
        .card h3 {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .balance-amount {
            font-size: 2.2rem;
            font-weight: bold;
            color: #1e40af;
        }
        .deposit { color: #16a34a; }
        .withdrawal { color: #dc2626; }
        .chart-wrapper {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .chart-wrapper h3 { margin-bottom: 20px; color: #374151; }
        .chart-container { position: relative; height: 300px; }
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #4b5563;
        }
        tr:hover { background: #f9fafb; }
        @media (max-width: 768px) {
            body { padding: 10px; }
            header h1 { font-size: 1.5rem; }
            .balance-amount { font-size: 1.8rem; }
            th, td { padding: 10px 8px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🏦 Banking Dashboard</h1>
        </header>

        <nav class="nav-bar">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('transactions.create') }}">New Transaction</a>
            <a href="{{ route('transactions.index') }}">All Transactions</a>
        </nav>

        <!-- Low Balance Alert -->
        <div class="alert-box" id="lowAlert">
            ⚠️ <strong>Low Balance Warning!</strong> Your balance is below the minimum required balance of ₱{{ number_format($account?->minimum_balance ?? 0, 2) }}.
        </div>

        <!-- Stats Cards -->
        <div class="grid">
            <div class="card">
                <h3>Current Balance</h3>
                <div class="balance-amount">₱{{ number_format($account?->balance ?? 0, 2) }}</div>
                <p style="margin-top: 8px; color: #6b7280;">Minimum: ₱{{ number_format($account?->minimum_balance ?? 0, 2) }}</p>
            </div>
            <div class="card">
                <h3>Total Deposits</h3>
                <div class="balance-amount deposit">+₱{{ number_format($deposits, 2) }}</div>
            </div>
            <div class="card">
                <h3>Total Withdrawals</h3>
                <div class="balance-amount withdrawal">-₱{{ number_format($withdrawals, 2) }}</div>
            </div>
        </div>

        <!-- Chart -->
        <div class="chart-wrapper">
            <h3>Transactions Overview</h3>
            <div class="chart-container">
                <canvas id="txChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="table-card">
            <h3>Recent Transactions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                    <tr>
                        <td>{{ $tx->created_at->format('Y-m-d H:i:s') }}</td>
                        <td style="font-weight: 500;">{{ ucfirst($tx->type) }}</td>
                        <td class="{{ $tx->type === 'deposit' ? 'deposit' : 'withdrawal' }}">
                            {{ $tx->type === 'deposit' ? '+' : '-' }}₱{{ number_format($tx->amount, 2) }}
                        </td>
                        <td>{{ $tx->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">
                            No transactions yet. <a href="{{ route('transactions.create') }}" style="color: #1e40af;">Record your first transaction →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Low Balance Check
        const balance = {{ $account?->balance ?? 0 }};
        const minBalance = {{ $account?->minimum_balance ?? 0 }};
        if (balance < minBalance) {
            document.getElementById('lowAlert').style.display = 'block';
        }

        // Chart
        const ctx = document.getElementById('txChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Total Deposits', 'Total Withdrawals'],
                datasets: [{
                    data: [{{ $deposits }}, {{ $withdrawals }}],
                    backgroundColor: ['#16a34a', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 14 } }
                    }
                }
            }
        });
    </script>
</body>
</html>