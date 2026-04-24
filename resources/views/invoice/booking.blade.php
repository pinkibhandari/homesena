<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #fff;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div style="padding: 30px 36px;">

        {{-- ── HEADER ── --}}
        <table width="100%" cellpadding="0" cellspacing="0"
            style="border-bottom: 1.5px solid #c0c0c0; padding-bottom: 18px; margin-bottom: 26px;">
            <tr>
                {{-- Logo + Company Details --}}
                <td width="55%" style="vertical-align: top;">
                    <img src="{{ public_path('assets/img/homesena_logo.png') }}"
                        style="width: 150px; height: auto;margin-left: -10px; display: block; margin-bottom: 10px;"
                        alt="HomeSena Logo">
                    <div style="font-size: 11px; line-height: 1.75; color: #1a1a1a; font-family: Arial, sans-serif;">
                        <strong>HomeSena Services</strong><br>
                        <strong>Registered Office:-</strong>Gaur City Mall,<br>
                        Greater Noida, UP -201318 <br>
                        <strong>Email:-</strong> support@homesena.com<br>
                        <strong>Telephone:-</strong> +91-8595081189<br>
                        <strong>Website:-</strong> www.homesena.com
                    </div>
                </td>
                {{-- Invoice Title --}}
                <td width="45%"
                    style="vertical-align: top; text-align: right; font-family: Arial, sans-serif;
                    font-size: 18px; font-weight: bold; letter-spacing: 1px; color: #111; padding-top: 4px;">
                    BOOKING INVOICE
                </td>
            </tr>
        </table>

        {{-- ── INFO SECTION ── --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 4px;">
            <tr>

                {{-- LEFT COLUMN (Customer) --}}
                <td width="44%" style="vertical-align: top;">
                    <table width="100%" cellpadding="0" cellspacing="0">

                        {{-- Customer Name --}}
                        <tr>
                            <td
                                style="padding-top: 0; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Customer
                                    Name</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">{{ $booking->user->name }}</span>
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
        margin-top: 8px; margin-bottom: 4px;">Phone
                                    Number</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
        line-height: 1.55;">{{ $booking->user->phone }}</span>
                            </td>
                        </tr>

                        {{-- Invoice No --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Invoice No.</span>
                                <span style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">{{ $invoiceNumber }}</span>
                            </td>
                        </tr>

                        {{-- Delivery Address --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Delivery
                                    Address</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">{{ $booking->address->address ?? 'N/A' }},{{ $booking->address->area_name ?? 'N/A' }}</span>
                            </td>
                        </tr>

                        {{-- Invoice Date --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Invoice
                                    Date</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">{{ $booking->created_at->format('d M, Y') }}</span>
                            </td>
                        </tr>

                        {{-- State Name & Code --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">State
                                    Name &amp; Code</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">UP
                                    16</span>
                            </td>
                        </tr>

                        {{-- Place of Supply --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Place
                                    of Supply</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">Noida</span>
                            </td>
                        </tr>

                    </table>
                </td>

                {{-- SPACER --}}
                <td width="12%" style="vertical-align: top;">&nbsp;</td>

                {{-- RIGHT COLUMN (Provider) --}}
                <td width="44%" style="vertical-align: top;">
                    <table width="100%" cellpadding="0" cellspacing="0">

                        {{-- Section Heading --}}
                        <tr>
                            <td
                                style="padding-top: 0; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13.5px; color: #111;
                                    letter-spacing: 0.3px;">DELIVERY
                                    SERVICE PROVIDER</span>
                            </td>
                        </tr>

                        {{-- Business GSTIN --}}
                        {{-- <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Business GSTIN</span>
                                <span style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">06AABCU7755Q1ZK</span>
                            </td>
                        </tr> --}}

                        {{-- Business Name --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Business
                                    Name</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">Your
                                    Company Pvt Ltd</span>
                            </td>
                        </tr>

                        {{-- Address --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">Address</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">Company
                                    Address</span>
                            </td>
                        </tr>

                        {{-- State Name & Code --}}
                        <tr>
                            <td
                                style="padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #cccccc;
                                font-family: Arial, sans-serif;">
                                <span
                                    style="display: block; font-weight: bold; font-size: 13px; color: #111;
                                    margin-bottom: 4px;">State
                                    Name &amp; Code</span>
                                <span
                                    style="display: block; font-size: 12.5px; color: #1a1a1a;
                                    line-height: 1.55;">UP
                                    16</span>
                            </td>
                        </tr>

                    </table>
                </td>

            </tr>
        </table>

        {{-- ── ITEMS TABLE ── --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th
                        style="background-color: #f2f2f2; padding: 9px 10px; font-size: 13px; font-weight: bold;
                        color: #111; text-align: left; border-top: 1px solid #bbb; border-bottom: 1px solid #bbb;
                        font-family: Arial, sans-serif; width: 52%;">
                        Items</th>
                    <th
                        style="background-color: #f2f2f2; padding: 9px 10px; font-size: 13px; font-weight: bold;
                        color: #111; text-align: right; border-top: 1px solid #bbb; border-bottom: 1px solid #bbb;
                        font-family: Arial, sans-serif; width: 28%;">
                        &nbsp;</th>
                    <th
                        style="background-color: #f2f2f2; padding: 9px 10px; font-size: 13px; font-weight: bold;
                        color: #111; text-align: right; border-top: 1px solid #bbb; border-bottom: 1px solid #bbb;
                        font-family: Arial, sans-serif; width: 20%;">
                        Taxable Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->slots as $slot)
                    <tr>
                        <td
                            style="padding: 11px 10px; border-bottom: 1px solid #e5e5e5; vertical-align: top;
                            font-family: Arial, sans-serif;">
                            <span
                                style="display: block; font-weight: bold; font-size: 13px;
                                color: #111;">{{ $slot->service_name }}</span>
                            <span
                                style="display: block; font-size: 11px; color: #666;
                                margin-top: 3px;">SAC:
                                999799</span>
                        </td>
                        <td
                            style="padding: 11px 10px; border-bottom: 1px solid #e5e5e5; text-align: right;
                            font-size: 12.5px; color: #444; vertical-align: top;
                            font-family: Arial, sans-serif;">
                            Gross Amount</td>
                        <td
                            style="padding: 11px 10px; border-bottom: 1px solid #e5e5e5; text-align: right;
                            font-size: 12.5px; color: #111; white-space: nowrap; vertical-align: top;
                            font-family: Arial, sans-serif;">
                            Rs. {{ number_format($slot->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── TOTALS ── --}}
        @php $gross = $booking->total_amount; @endphp

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 52%; padding: 7px 10px; font-family: Arial, sans-serif;"></td>
                <td
                    style="padding: 7px 10px; text-align: right; font-size: 12.5px; color: #444;
                    font-family: Arial, sans-serif;">
                    Discount</td>
                <td
                    style="width: 20%; padding: 7px 10px; text-align: right; font-size: 12.5px; color: #111;
                    white-space: nowrap; font-family: Arial, sans-serif;">
                    - Rs. 0</td>
            </tr>
            <tr>
                <td style="width: 52%; padding: 7px 10px; font-family: Arial, sans-serif;"></td>
                <td
                    style="padding: 7px 10px; text-align: right; font-size: 12.5px; color: #444;
                    font-family: Arial, sans-serif;">
                    Gross Amount</td>
                <td
                    style="width: 20%; padding: 7px 10px; text-align: right; font-size: 12.5px; color: #111;
                    white-space: nowrap; font-family: Arial, sans-serif;">
                    Rs. {{ number_format($gross, 2) }}</td>
            </tr>
            <tr>
                <td
                    style="padding: 10px 10px; font-size: 13.5px; font-weight: bold; color: #111;
                    border-top: 1.5px solid #bbb; text-align: left;
                    font-family: Arial, sans-serif;">
                    TOTAL AMOUNT</td>
                <td style="padding: 10px 10px; border-top: 1.5px solid #bbb; font-family: Arial, sans-serif;"></td>
                <td
                    style="width: 20%; padding: 10px 10px; font-size: 13.5px; font-weight: bold; color: #111;
                    border-top: 1.5px solid #bbb; text-align: right; white-space: nowrap;
                    font-family: Arial, sans-serif;">
                    Rs. {{ number_format($gross, 0) }}</td>
            </tr>
        </table>

    </div>
</body>

</html>
