<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAW Curs 7: Teme</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
    <link rel="manifest" href="./site.webmanifest">
    <link rel="mask-icon" href="./safari-pinned-tab.svg" color="#3e433d">
    <meta name="msapplication-TileColor" content="#3e433d">
    <meta name="theme-color" content="#3e433d">
</head>
<body>
    <h1>DAW Curs 7: Exercitiul 7</h1>
    <p>Cerinta: Autentificare folosind o imagine</p>

    <form action="POST">
        <div>
            <label for="username">Username: </label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password image: </label>
            <input type="file" name="password" id="password" required>
        </div>
        <div>
            <input type="submit" value="Trimite informatiile pentru a te autentifica folosind imaginea!">
        </div>
    </form>

    <?php
        ///////////////////
        // Exercitiul 07 //
        ///////////////////

        // Cerinta: Autentificare folosind o imagine

        // La ce va referiti prin autentificare folosind o imagine?
        // Username-ul tre sa existe, dar in loc de parola sa dau un fisier
        // Imaginea tre sa intoarca o parola
        // E la fel ca la torrente care imi genereaza o parola
        // Tool online care genereaza o parola pe baza unei imagini
            // https://webencrypt.org/onlinetoolsjs/md5_checksum.html

    ?>
</body>
</html>
