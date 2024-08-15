<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication Backup Codes</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        .codes {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .code {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .important {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Two-Factor Authentication Backup Codes</h2>

        <p>These codes are essential for regaining access to your account if you lose your authenticator app. Please store them securely in a safe place.</p>

        <div class="codes">
            @foreach ($backupCodes as $code)
                <div class="code">{{ $loop->iteration }}:  {{ $code }}</div>
            @endforeach
        </div>

        <div class="important">
            <strong>Important:</strong> Keep these codes confidential. Do not share them with anyone.
        </div>
    </div>
</body>
</html>
