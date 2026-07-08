<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('page-title', 'PDF Export')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
        }
        h1, h2, h3, h4, h5, h6 {
            margin: 0.5em 0 0.2em 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #888;
            padding: 4px 6px;
            text-align: left;
        }
        hr {
            border: none;
            border-top: 1px solid #888;
            margin: 16px 0;
        }
        ul, ol {
            margin: 0 0 0 20px;
            padding: 0;
        }
        p {
            margin: 0 0 8px 0;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
