<!-- resources/views/errors/404.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo/small-logo.png">
    <title>404 - Page Not Found</title>
    <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f1f3;
            color: #343a40;
            margin: 0;
            text-align: center;
            padding: 0
        }

        .not-found-box {
            padding: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            min-height: 100%;
            flex-direction: column;
            overflow: auto;
            min-height: 100vh;
        }

        .not-found-box h1 {
            font-size: 200px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #d1cdd5;
            line-height: 200px;
            margin: 0
        }

        .not-found-box span {
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #000;
            display: block;
        }

        .not-found-box p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .not-found-box .btn {
            padding: 10px 20px;
            background-color: #0b2c58;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin: 0 auto;
        }

        .not-found-box .btn:hover,
        .not-found-box .btn:active,
        .not-found-box .btn:focus {
            background-color: #5e2893;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Media */
        @media (max-width:500px) {
            .not-found-box h1 {
                font-size: 100px;
                line-height: 100px;
            }

            .not-found-box span {
                font-size: 25px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="not-found-box">
        <h1>404 </h1>
        <span>
            Page Not Found
        </span>
        <p>Sorry, the page you are looking for does not exist.</p>
        <a href="{{ route('admin.dashboard') }}" class="btn">Go to Dashboard</a>
    </div>
</body>

</html>
