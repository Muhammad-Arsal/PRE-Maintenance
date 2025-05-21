<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            color: #4a4a4a;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-circle  {
            width: 60px;
            height: 60px;
            margin: auto;
            line-height: 60px;
        }

        .logo-circle img  {
            width: 60px;
            height: 60px;
            
        }

        .branding {
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .subtitle {
            color: #888;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .invoice-info,
        .bank-details {
            font-size: 14px;
            margin-top: 20px;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .table th,
        .table td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            background: #e7e0d9;
            text-transform: uppercase;
            font-size: 12px;
        }

        .totals {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
        }

        .totals div {
            margin: 5px 0;
        }

        .highlight {
            font-weight: bold;
        }

        .dot {
            text-align: center;
            font-size: 24px;
            color: #bb3e3e;
            margin-top: -15px;
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="header">
            <div class="logo-circle"> <img alt="modern admin logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/logo/logo.png'))) }}"></div>
            <div class="branding">{{ $invoice->contractor->name ?? '' }}</div>
            <div class="subtitle">{{ $invoice->contractor->company_name ?? '' }}</div>
        </div>

        <div class="flex invoice-info">
            <div>
                <strong>Invoice No:</strong> {{ $invoiceNo }} <br>
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}<br>
            </div>
        </div>

        <div class="dot">•</div>

        <table class="table">
            <thead>
                <tr>
                    <th>Job task</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobDetails as $jobDetail)
                    <tr><td>{{$jobDetail->description}}</td><td>${{ $jobDetail->price }}</td>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div>Subtotal: <span class="highlight">${{ $subtotal }}</span></div>
            <div>Tax {{ $invoice->vat_rate }}%: <span class="highlight">${{ $invoice->vat }}</span></div>
            <div class="highlight">Total: ${{ $subtotal + $invoice->vat }}</div>
        </div>

        <div class="bank-details">
            <strong>Bank Details</strong><br>
            Borcele Bank<br>
            Account Name: Avery Davis<br>
            Account No.: 0123 4567 8901
        </div>

    </div>
</body>
</html>
