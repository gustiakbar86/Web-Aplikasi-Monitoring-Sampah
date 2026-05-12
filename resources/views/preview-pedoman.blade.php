<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Petunjuk Penggunaan Aplikasi</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #323639;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
    </style>
</head>
<body>
    <iframe src="{{ route('pedoman.stream') }}?t={{ time() }}#toolbar=1&navpanes=1&pagemode=thumbs&zoom=100" type="application/pdf">
    </iframe>
</body>
</html>
