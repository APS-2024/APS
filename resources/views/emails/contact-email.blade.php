<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
</head>
<body>
    <h1>New Contact Form Submission</h1>
    <p><strong>First Name:</strong> {{ $contactData['first_name'] }}</p>
    <p><strong>Last Name:</strong> {{ $contactData['last_name'] }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Skype Contact:</strong> {{ $contactData['skype_contact'] }}</p>
    <p><strong>WhatsApp Contact:</strong> {{ $contactData['whatsapp_contact'] }}</p>
</body>
</html>
