<?php
const APP_NAME = 'Homomni';
const APP_BASE = '/';
const APP_ICON = 'icon.png';
const APP_FAVICON = 'favicon.ico';
const APP_LOGO = 'logo.svg';
const APP_COLOR = '#788eff';
const APP_AUTHOR = 'SIGUI Kessé Emmanuel';
?>
<!DOCTYPE html>
<html lang="fr-CI">
    <head>
        <base href="<?= APP_BASE ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= APP_NAME ?></title>
        <meta name="description" content="Simple & Cool">
        <meta name="keywords" content="<?= APP_NAME ?>, posts, chats, users, simple, cool.">
        <meta name="author" content="<?= APP_AUTHOR ?>">
        <meta name="robots" content="index, follow">
        <meta name="revisit-after" content="1 days">
        <meta name="language" content="French">
        <meta name="geo.placename" content="Abidjan">
        <meta name="geo.region" content="CI-AB">
        <meta name="geo.position" content="5.0;7.0">
        <meta name="ICBM" content="5.0,7.0">
        <meta name="theme-color" content="<?= APP_COLOR ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= APP_ICON ?>">
        <link rel="icon" type="image/x-icon" sizes="16x16" href="<?= APP_FAVICON ?>">
        <!--link rel="manifest" href="<?= APP_ICON ?>"-->
        <link rel="mask-icon" href="<?= APP_ICON ?>" color="<?= APP_COLOR ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?= APP_FAVICON ?>">

        <!-- Open Graph -->
        <meta property="og:title" content="<?= APP_NAME ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?= APP_BASE ?>">
        <meta property="og:image" content="<?= APP_ICON ?>">
        <meta property="og:description" content="Simple & Cool">
        <meta property="og:site_name" content="<?= APP_NAME ?>">
        <meta property="og:locale" content="fr_CI">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@<?= APP_NAME ?>">
        <meta name="twitter:title" content="<?= APP_NAME ?>">
        <meta name="twitter:description" content="Simple & Cool">
        <meta name="twitter:image" content="<?= APP_ICON ?>">

        <!-- Google+ -->
        <meta itemprop="name" content="<?= APP_NAME ?>">
        <meta itemprop="description" content="Simple & Cool">
        <meta itemprop="image" content="<?= APP_ICON ?>">

        <!-- Apple -->
        <meta name="apple-mobile-web-app-title" content="<?= APP_NAME ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= APP_ICON ?>">

        <!-- Windows -->
        <meta name="msapplication-TileColor" content="<?= APP_COLOR ?>">
        <meta name="msapplication-TileImage" content="<?= APP_ICON ?>">
        <!--meta name="msapplication-config" content="<?= APP_ICON ?>"-->

        <!-- CSS -->
        <style>
        * {
            box-sizing: inherit;
            margin: 0;
            padding: 0;
        }

        :root {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #333;
            background-color: #fff;
        }

        #wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 100%;
            height: 100%;
        }

        header {
            color: <?= APP_COLOR ?>;
            margin-bottom: 1em;
        }

        footer {
            color: <?= APP_COLOR ?>;
            margin-top: 1em;
            font-size: 0.875em;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        form {
            display: flex;
            flex-direction: column;
            margin: 24px auto;
        }

        input, button {
            margin: 0.5em 0;
            font-size: 1em;
            padding: 0.5em;
        }

        button {
            background-color: <?= APP_COLOR ?>;
            color: #fff;
            border: none;
            border-radius: 0.25em;
            cursor: pointer;
        }

        </style>

        <!-- JS -->
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1><a href="<?= APP_BASE ?>" title="Home"><?= APP_NAME ?></a></h1>
            </header>
            <main>
                <p>Rejoignez-nous la communauté la plus simple et cool.</p>
                <form method="POST" action="/">
                    <input type="email" name="user[email]" placeholder="Entrez votre e-mail" required/>
                    <button type="submit" name="action" value="join">Rejoindre</button>
                </form>
            </main>
            <footer>
                <p>&copy; <?= date('Y') ?> <a href="<?= APP_BASE ?>" title="<?= APP_NAME ?>"><?= APP_NAME ?></a></p>
            </footer>
        </div>
    </body>
</html>