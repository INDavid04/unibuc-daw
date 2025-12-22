<?php 
session_start(); 

require_once('../login/database.php');
$db = Database::getInstance()->getConnection();

$eroare = null;
try {
    /// Verifica daca e spectator
    $stmt = $db->prepare("select * from spectator where id_utilizator = ?");
    $stmt->execute([$_SESSION['id_utilizator']]);
    $eSpectator = $stmt->fetch();
    if (!$eSpectator) {
        throw new Exception("Nu aveti cont de spectator. <a href='../'>Apasa aici pentru a te intoarce acasa.</a>");
    } else {
        /// Salveaza toate biletele in ordine descrescatoare
        $stmt = $db->prepare("
            select id_bilet
            from bilet
            where id_spectator = ?
            order by id_bilet desc
        ");
        $stmt->execute([$_SESSION['id_utilizator']]);
        $bilete = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $eroare = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IND | DAW Vezi biletele tale</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#3e433d">
    <meta name="msapplication-TileColor" content="#3e433d">
    <meta name="theme-color" content="#3e433d">
</head>
<body>
    <header>
        <a href="../">
            <svg width="700.000000pt" height="700.000000pt" viewBox="0 0 700.000000 700.000000">
            <g transform="translate(0.000000,700.000000) scale(0.100000,-0.100000)"
            fill="currentColor" stroke="none">
            <path d="M3759 6736 c-2 -2 -56 -6 -119 -10 -63 -3 -130 -8 -150 -11 -19 -2 -60 -7 -90 -9 -78 -8 -263 -32 -315 -42 -10 -2 -37 -6 -59 -10 -22 -3 -43 -7 -46 -9 -3 -2 -26 -6 -50 -10 -25 -3 -61 -10 -80 -15 -19 -5 -46 -11 -60 -13 -14 -3 -95 -24 -180 -47 -960 -259 -1677 -820 -2049 -1601 -157 -329 -242 -653 -285 -1074 -13 -136 -15 -644 -2 -740 3 -22 8 -62 11 -90 11 -109 15 -135 46 -290 114 -588 388 -1101 805 -1505 298 -289 690 -532 1119 -692 88 -33 86 -32 230 -77 267 -84 657 -164 900 -186 39 -3 77 -8 85 -10 8 -2 56 -7 105 -10 50 -3 110 -9 135 -12 25 -3 717 -8 1538 -10 l1492 -4 0 3241 0 3241 -1489 -1 c-819 -1 -1490 -2 -1492 -4z m1969 -701 c4 -4 7 -1147 7 -2542 l1 -2534 -941 4 c-517 2 -962 7 -990 10 -27 4 -75 9 -105 12 -130 13 -168 17 -225 25 -33 5 -73 12 -90 15 -16 3 -68 12 -115 21 -105 19 -330 76 -430 110 -179 61 -432 177 -525 240 -20 13 -38 24 -41 24 -10 0 -166 112 -239 171 -108 87 -235 216 -315 319 -19 25 -37 47 -40 50 -9 8 -93 140 -131 205 -98 167 -216 474 -246 635 -2 14 -8 43 -13 65 -15 69 -39 227 -46 305 -3 41 -8 89 -10 105 -5 43 -5 442 1 490 2 22 7 65 10 95 8 81 25 203 31 231 3 13 7 34 10 47 2 12 6 32 8 45 14 68 31 132 63 232 132 413 366 762 679 1014 49 39 94 76 101 81 98 79 361 220 561 299 240 96 594 179 882 206 25 3 68 7 95 10 28 3 99 8 160 11 60 3 111 6 112 7 5 6 1776 -2 1781 -8z"/>
            <path d="M2208 5174 c-361 -54 -636 -358 -655 -725 -20 -381 236 -718 610 -802 89 -20 269 -16 357 8 350 94 584 400 582 760 -3 473 -422 829 -894 759z"/>
            <path d="M2089 3473 c-7 -189 1 -1034 10 -1093 53 -347 289 -643 612 -764 193 -73 409 -78 609 -14 282 90 499 299 606 585 22 58 31 91 49 178 6 29 15 185  15 269 l0 40 96 -100 c90 -92 619 -633 884 -903 l115 -117 280 1 280 0 3 973 2 972 -284 0 -284 0 -1 -517 c0 -285 0 -536 0 -559 l-1 -41 -114 116 c-62 64 -156 159 -207 212 -52 53 -247 252 -433 443 l-339 346 -284 0 c-155 0 -283 -3 -284 -7 0 -5 -1 -224 -1 -488 -1 -327 -5 -496 -13 -530 -53 -228 -283 -377 -501 -324 -142 34 -255 135 -306 274 -22 59 -22 71 -24 560 -2 275 -3 503 -3 508 -1 4 -109 7 -241 7 l-240 0 -1 -27z"/>
            </g>
            </svg>
            <span>Organizare evenimente</span>
        </a>
        <nav>
            <ul>
                <li><a href="../descrierea-aplicatiei/">Descrierea aplicatiei</a></li>
                <?php if (!isset($_SESSION['nume'])): ?>
                    <li><a href="../login/">Creeaza cont / Autentifica-te</a></li>
                <?php else: ?>
                    <li><a href="../login/user-info.php">Despre <?= htmlspecialchars($_SESSION['nume']); ?></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Biletele mele</h1>
        <?php
            if ($eroare) {
                echo "$eroare";
            } else {
                if (empty($bilete)) {
                    echo "Nu aveti niciun bilet cumparat";
                } else {
                    ?>
                        <p>Biletele incepand cu cele mai recente</p>
                        <ol>
                            <?php
                                foreach ($bilete as $bilet) {
                            ?>
                                <li>
                                    <a href="./genereaza-bilet.php?id_bilet=<?= $bilet['id_bilet'] ?>" target="_blank" rel="noopener noreferrer">Vezi bilet #<?= $bilet['id_bilet']?></a>
                                </li>
                            <?php
                                }
                            ?>
                        </ol>
                    <?php
                }
            }
        ?>
    </main>

    <footer>
        <h2>Mergi catre</h2>
        <ul>
            <li><a href="../">Pagina principala</a></li>
            <li><a href="../descrierea-aplicatiei/">Descrierea aplicatiei</a></li>
            <li><a href="../curs-autentificare-prin-imagine/">Tema cu autentificare prin imagine</a></li>
            <li><a href="../curs-generare-document/" target="_blank" rel="noopener noreferrer">Tema cu generare document</a></li>
            <li><a href="../curs-contact/">Tema cu captcha pe formularul de contact</a></li>
        </ul>
        <div>
            <p>All rights reserved &copy; 2025</p>
            <p>Made with love by <a href="http://indavid04.github.io/portofolio" target="_blank" rel="noopener noreferrer">INDavid04</a></p>
        </div>
    </footer>
</body>
</html>
