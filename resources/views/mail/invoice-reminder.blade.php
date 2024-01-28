<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }

        .invoice-details {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ $title }}</h1>
    <p>Dear {{ $customer_name }},</p>
    <p>We would like to remind you that Invoice {{$invoiceNumber}} remains outstanding.
        As the due date of {{ $dueDate }} has passed,
        we kindly request your prompt attention to settle the outstanding amount.</p>
    <br>
    <p>Here are the details of the invoice:
    </p>
    <div class="invoice-details">
        <ul>

            <li>Invoice Number: {{ $invoiceNumber }}</li>
            <li>Invoice Date & time: {{ \Carbon\Carbon::parse($invoiceDate)->addHour() }}</li>

            <li>Due Date: {{ $dueDate }}</li>
            <li>Remaining days: <b>{{ $remaining_days }} days </b></li>
            <li>Total amount: ₦<b>{{ $totalFinalCost }}</b></li>
        </ul>

    </div>
    <br>

    <p>To make the payment process as convenient as possible, we have included our payment details below:</p>
    <ul>
        <li><a href="https://sandbox-demo.pixel-pay.net:3000/">Payment Here</a></li>
    </ul>

    <p>If you have already made the payment, please accept our thanks, and we kindly ask you to disregard this reminder.
    </p>
    <p>However, if you are experiencing any difficulties or have any questions regarding the invoice, please don't hesitate to contact our support team at [Contact Information].</p>
    <br>
    <p>Thank you for your prompt attention to this matter.</p>
</div>
</body>
</html>
