<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 6rem;
            margin: 0;
        }
        p {
            font-size: 1.5rem;
            margin: 10px 0 20px;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.2rem;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Oops! You're lost.</h2>
        <p>The page you are looking for was not found.</p>
        <a href="{{ url('/') }}">Go back to Homepage</a>
    </div>
</body>
</html>