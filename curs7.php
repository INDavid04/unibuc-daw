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
    <header>
        <h1>DAW Curs 7: Exercitiul 7</h1>
    </header>

    <main>
        <p>Cerinta: Autentificare folosind o imagine</p>
        <!-- Am folosit enctype="multipart" intrucat incarcam un fisier (poza pentru autentificare) -->
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
            /// INTREBARE: La ce va referiti prin autentificare folosind o imagine?
                // Username-ul tre sa existe, dar in loc de parola sa dau un fisier
                // Imaginea tre sa intoarca o parola
            /// UTIL: Tool online care genereaza o parola pe baza unei imagini
                // https://webencrypt.org/onlinetoolsjs/md5_checksum.html
                // https://www.php.net/manual/en/function.openssl-sign.php

            // Porneste sau reia sesiunea PHP pentru a pastra starea de autentificare
            session_start();

            $users_file = "utilizatori.txt";
            $message = "";

            // Verifica daca formularul a fost trimis
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Preia datele din formular
                $username = $_POST["username"];
                $image = $_FILES["password"]["tmp_name"];

                // Verifica daca fisierul a fost incarcat cu succes
                if (is_uploaded_file($image)) {
                    // Calculeaza hash-ul fisierului incarcat
                    $hash_imagine = hash_file('sha256', $image);

                    // Citeste utilizatorii existenti din fisier
                    $users = file_exists($users_file) ? file($users_file, FILE_IGNORE_NEW_LINES) : [];
                    
                    // Verifica daca utilizatorul exista si daca hash-ul imaginii corespunde
                    $found = false;
                    foreach ($users as $line) {
                        // username:hash_imagine
                        list($storedUsername, $storedHash) = explode(":", $line);
                        
                        // Verifica daca username-ul si hash-ul imaginii corespund
                        if ($storedUsername === $username) {
                            $found = true;
                            if ($storedHash === $hash_imagine) {
                                $_SESSION["autentificat"] = $username;
                                $message = "Autentificare reusită! Bine ai venit, $username!";
                            } else {
                                $message = "Imagine incorecta pentru utilizatorul $username.";
                            }
                        }
                    }

                    // Daca utilizatorul nu exista, il adauga
                    if (!$found) {
                        file_put_contents($users_file, "$username:$hash_imagine\n", FILE_APPEND);
                        $message = "Utilizator nou inregistrat: $username.";
                    }
                }
            }
            
            // Afiseaza mesajul respectiv
            echo "$message";
        ?>
    </main>

    <footer>
        <h2>Mergi catre</h2>
        <ul>
            <li><a href="./index.html">Pagina principala</a></li>
            <li><a href="./descrierea-aplicatiei.html">Descrierea aplicatiei</a></li>
            <li><a href="./curs7.php">Cursul 7</a></li>
        </ul>
        <div>
            <p>All rights reserved &copy; 2025</p>
            <p>Made with love by <a href="http://indavid04.github.io/portofolio" target="_blank" rel="noopener noreferrer">INDavid04</a></p>
        </div>
    </footer>
</body>
</html>
