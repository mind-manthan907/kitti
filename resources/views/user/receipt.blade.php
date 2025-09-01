<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Noto Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            color: #d4af37;
            /* gold color */
            margin: 0;
        }

        .header p {
            font-size: 14px;
            color: #555;
            margin: 5px 0 0;
        }

        .receipt-title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
            color: #444;
        }

        .details,
        .payment-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .details td,
        .payment-info td {
            padding: 10px 5px;
        }

        .details td:first-child,
        .payment-info td:first-child {
            font-weight: bold;
            width: 200px;
            color: #555;
        }

        .details td:last-child,
        .payment-info td:last-child {
            color: #333;
        }

        .footer {
            text-align: center;
            border-top: 2px solid #d4af37;
            padding-top: 20px;
            font-size: 12px;
            color: #777;
        }

        .amount {
            color: #d4af37;
            font-weight: bold;
            font-size: 18px;
        }

        .paid-tag {
            display: inline-block;
            padding: 4px 12px;
            background-color: #d4af37;
            color: #fff;
            font-weight: bold;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>{{ $company_name }}</h1>
        <p>{{ $company_address }}</p>
        <p>by Ornisha Jewellers</p>
    </div>

    <div class="receipt-title">Payment Receipt</div>

    <table class="details">
        <tr>
            <td>Transaction ID:</td>
            <td>{{ $payment->transaction_reference }}</td>
        </tr>
        <tr>
            <td>Payment Date:</td>
            <td>{{ \Carbon\Carbon::parse($payment->payment_completed_at)->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td>Status:</td>
            <td><span class="paid-tag">Paid</span></td>
        </tr>
    </table>

    <table class="payment-info">
        <tr>
            <td>Amount Paid:</td>
            <td class="amount">₹{{ number_format($payment->amount) }}</td>
        </tr>
        <tr>
            <td>Plan Name:</td>
            <td>{{ $payment->registration->investmentPlan->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Plan Duration:</td>
            <td>{{ $payment->registration->investmentPlan->duration_months ?? 'N/A' }} Months</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for choosing {{ $company_name }}.<br>
        We appreciate your trust on us.
    </div>
</body>

</html>