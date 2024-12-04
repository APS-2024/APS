<!DOCTYPE html>
<html>
<head>
    <title>Advertiser Form</title>
</head>
<body>
    <h1>New Advertiser Form Submission</h1>
    <p><strong>First Name:</strong> {{ $contactData['first_name'] }}</p>
    <p><strong>Last Name:</strong> {{ $contactData['last_name'] }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Message:</strong> {{ $contactData['note'] }}</p>
</body>
</html>
