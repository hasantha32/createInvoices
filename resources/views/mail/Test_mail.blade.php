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
    </style>
</head>
<body>
<div class="container">
    <h1>{{ $title }}</h1>
    <p>Dear {{ $customer_name }},</p>
    <p>We appreciate your purchase and would like to inform you that your recent transaction for the purchase of
        {{ $title }} has been successfully processed through our IPG merchant.</p>
    <br>
    <p>Please find the details of your transaction and the invoice below:</p>
    <div class="invoice-details">
        <p>Invoice Number: {{ $invoice_number }}</p>
        <p>Invoice Date: {{ $date_of_transaction }}</p>
        <p>Description of Product/Service: {{ $Description_of_product }}</p>
        <p>Quantity: {{ $Quantity }}</p>
        <p>Transaction Amount: {{ $Transaction_amount }}</p>
    </div>
    <br>
    <p>Download Invoice:</p>
    <p>[Attach the invoice or provide a link to download the invoice]</p>
    <br>
    <p>If you have any questions or concerns regarding this attached invoice, please do not hesitate to contact us at [Merchant Contact Information].</p>
    <br>
    <p>Thank you for choosing PixelPay.</p>
</div>
</body>
</html>
