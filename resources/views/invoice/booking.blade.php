<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tax Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 6px;
            vertical-align: top;
        }
        .border-bottom {
            border-bottom: 1px solid #ddd;
        }
        .right {
            text-align: right;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }
        .mt-10 {
            margin-top: 10px;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <table>
        <tr>
            <td width="60%">
                <h3>Your Company Name</h3>
                Address line 1<br>
                City, State - Pincode<br>
                Email: support@email.com<br>
                Phone: +91-XXXXXXXXXX<br>
                GSTIN: 06ABCDE1234F1Z5
            </td>
            <td width="40%" class="title">
                TAX INVOICE
            </td>
        </tr>
    </table>

    <!-- Customer + Provider -->
    <table class="mt-10">
        <tr>
            <!-- Customer -->
            <td width="50%">
                <div class="border-bottom"><strong>Customer Name</strong><br>{{ $booking->user->name }}</div>

                <div class="border-bottom mt-10"><strong>Invoice No</strong><br>INV-{{ $booking->id }}</div>

                <div class="border-bottom mt-10"><strong>Delivery Address</strong><br>{{ $booking->address ?? 'N/A' }}</div>

                <div class="border-bottom mt-10"><strong>Invoice Date</strong><br>{{ $booking->created_at->format('d M Y') }}</div>

                <div class="border-bottom mt-10"><strong>State</strong><br>Haryana 06</div>

                <div class="border-bottom mt-10"><strong>Place of Supply</strong><br>Haryana 06</div>
            </td>

            <!-- Provider -->
            <td width="50%">
                <div class="border-bottom"><strong>Delivery Service Provider</strong></div>

                <div class="border-bottom mt-10"><strong>GSTIN</strong><br>06AABCU7755Q1ZK</div>

                <div class="border-bottom mt-10"><strong>Business Name</strong><br>Your Company Pvt Ltd</div>

                <div class="border-bottom mt-10"><strong>Address</strong><br>Company Address</div>

                <div class="border-bottom mt-10"><strong>State</strong><br>Haryana 06</div>
            </td>
        </tr>
    </table>

    <!-- Items -->
    <table class="mt-10" border="1">
        <thead>
            <tr>
                <th align="left">Items</th>
                <th class="right">Taxable Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->slots as $slot)
            <tr>
                <td>
                    {{ $slot->service_name }}<br>
                    <small>SAC: 999799</small>
                </td>
                <td class="right">₹ {{ number_format($slot->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Calculation -->
    @php
        $gross = $booking->total_amount;
        $cgst = $gross * 0.09;
        $sgst = $gross * 0.09;
        $total = $gross + $cgst + $sgst;
    @endphp

    <table class="mt-10">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                <table>
                    <tr>
                        <td>Gross Amount</td>
                        <td class="right">₹ {{ number_format($gross, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td class="right">₹ 0</td>
                    </tr>
                    <tr>
                        <td>Taxable Value</td>
                        <td class="right">₹ {{ number_format($gross, 2) }}</td>
                    </tr>
                    <tr>
                        <td>CGST @9%</td>
                        <td class="right">₹ {{ number_format($cgst, 2) }}</td>
                    </tr>
                    <tr>
                        <td>SGST @9%</td>
                        <td class="right">₹ {{ number_format($sgst, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td>TOTAL</td>
                        <td class="right">₹ {{ number_format($total, 0) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>

</body>
</html>