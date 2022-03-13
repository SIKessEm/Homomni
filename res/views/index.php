<!DOCTYPE html>
<html lang="fr-CI">
    <head>
        <base href="<?= APP_BASE ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title ?? APP_NAME ?></title>
        <meta name="description" content="<?= $description ?>">
        <meta name="keywords" content="<?= APP_NAME . $keywords ?>">
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
        <meta property="og:title" content="<?= $title ?? APP_NAME ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?= APP_BASE ?>">
        <meta property="og:image" content="<?= APP_ICON ?>">
        <meta property="og:description" content="<?= $description ?>">
        <meta property="og:site_name" content="<?= APP_NAME ?>">
        <meta property="og:locale" content="fr_CI">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@<?= APP_NAME ?>">
        <meta name="twitter:title" content="<?= $title ?? APP_NAME ?>">
        <meta name="twitter:description" content="<?= $description ?>">
        <meta name="twitter:image" content="<?= APP_ICON ?>">

        <!-- Google+ -->
        <meta itemprop="name" content="<?= APP_NAME ?>">
        <meta itemprop="description" content="<?= $description ?>">
        <meta itemprop="image" content="<?= APP_ICON ?>">

        <!-- Apple -->
        <meta name="apple-mobile-web-app-title" content="<?= $title ?? APP_NAME ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= APP_ICON ?>">

        <!-- Windows -->
        <meta name="msapplication-TileColor" content="<?= APP_COLOR ?>">
        <meta name="msapplication-TileImage" content="<?= APP_ICON ?>">
        <!--meta name="msapplication-config" content="<?= APP_ICON ?>"-->

        <!-- CSS -->
        <?= style('index') ?>
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1><a href="<?= APP_BASE ?>" title="Home"><?= APP_NAME ?></a></h1>
            </header>
            <main>
                <?= $motivation ?: $description ?>
                <div><?= $form->generate() ?></div>
            </main>
            <footer>
                <p>&copy; <?= date('Y') ?> <a href="<?= APP_BASE ?>" title="<?= APP_NAME ?>"><?= APP_NAME ?></a></p>
            </footer>
        </div>
    </body>
</html>