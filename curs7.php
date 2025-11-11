<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAW Curs 7: Teme</title>
    <link rel="stylesheet" href="./styles.css">
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

    <form action="curs7.php" method="POST" enctype="multipart/form-data">
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
            // https://www.php.net/manual/en/function.openssl-sign.php

        session_start();

        $users_file = "utilizatori.txt";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"];
            $fisier = $_FILES["password"]["tmp_name"];

            if (is_uploaded_file($fisier)) {
                /// Calculeaza hash SHA-256 al fisierului
                $hash_imagine = hash_file('sha256', $fisier);
                $utilizatori = file_exists($users_file) ? file($users_file, FILE_IGNORE_NEW_LINES) : [];
                $gasit = false;
                foreach ($utilizatori as $linie) {
                    list($u, $h) = explode(":", $linie);
                    if ($u === $username) {
                        $gasit = true;
                        if ($h === $hash_imagine) {
                            $_SESSION["autentificat"] = $username;
                            $mesaj = "Autentificare reușită! Bine ai venit, $username.";
                        } else {
                            $mesaj = "Imagine incorectă pentru utilizatorul $username.";
                        }
                    }
                }

                if (!$gasit) {
                    // Salveaza noul utilizator impreuna cu hashul imaginii
                    file_put_contents($users_file, "$username:$hash_imagine\n", FILE_APPEND);
                    $mesaj = "Utilizator nou înregistrat! Acum poți folosi această imagine pentru autentificare.";
                }
            } else {
                $mesaj = "Nu ai selectat nicio imagine.";
            }
        }
        echo '$mesaj';
    ?>
</body>
</html>
