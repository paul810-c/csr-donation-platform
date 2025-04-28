<!DOCTYPE html>
<html>
<head>
    <title>Donation Confirmation</title>
</head>
<body>
<h1>Thank you for your donation</h1>

<p>Dear Employee {{ $donation->employeeId }},</p>

<p>You have successfully donated <strong>{{ number_format($donation->amount_in_cents / 100, 2) }} EUR</strong>.</p>


<p>– CSR Team</p>
</body>
</html>
