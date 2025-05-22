<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Artikel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 20px;
            font-size: 16px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .image {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .fund_usage_details {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .description {
            font-size: 16px;
            margin-top: 15px;
            margin-bottom: 20px;
        }



        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            padding: 20px;
            margin-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logo.png') }}" alt="Amal Produktif Logo">
        </div>
        <div class="content">
            <img src="{{ $article->getFirstMediaUrl('articles') }}" alt="Article Image" class="image">
            <div class="title">{{ $article->title }}</div>
            <div class="description">{{ $article->description }}</div>
            <a href="{{ $articleUrl }}" style="display: inline-block; background: #4CAF50; color: white !important;
            text-decoration: none; font-weight: bold; font-size: 18px; padding: 14px 28px;
            border-radius: 10px; text-align: center;">Lihat Detail</a>
        </div>
        <div class="footer">
            &copy; 2025 Amal Produktif | <a href="#">Unsubscribe</a>
        </div>
    </div>
</body>

</html>