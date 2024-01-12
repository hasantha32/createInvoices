<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
</head>
<body>
<p>Dear {{ $customer_name }}, </p>
<p>We appreciate your purchase and would like to inform you that your recent transaction for the purchase of [Product/Service] has been successfully processed through our IPG merchant.
</p>
<br>
<p>Please find the details of your transaction and the invoice below:</p>
<br>
<p>Invoice Number: {{ $invoice_number }}</p>
<p>Transaction Date:</p>
<p>Quantity:{{ $Quantity }}</p>
<p>Transaction Amount: {{$Transaction_amount}}</p>
<br>
<p>Download Invoice:</p>
<p>[Attach the invoice or provide a link to download the invoice]
</p>
<br>
{{--<p>Title: {{ $title }}</p>--}}
<p>If you have any questions or concerns regarding this transaction or the attached invoice, please do not hesitate to contact us at [Merchant Contact Information].
</p>
<br>
<p>Thank you for choosing PixelPay.</p>
</body>
</html>
