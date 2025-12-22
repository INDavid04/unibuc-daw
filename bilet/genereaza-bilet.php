<?php
session_start();

$eroare = null;

try {
    if (!isset($_SESSION['id_utilizator'])) {
        throw new Exception("Trebuie sa fii autentificat pentru a vedea biletele");
    }

    require_once('../login/database.php');
    require_once('../assets/fpdf186/fpdf.php');

    $db = Database::getInstance()->getConnection();

    $id_bilet = $_GET['id_bilet'] ?? null;
    $id_eveniment = $_GET['id_eveniment'] ?? null;
    $loc = $_GET['loc'] ?? null;
    $pret = $_GET['pret'] ?? null;

    /// Verifica existenta biletului
    if (!$id_bilet) {
        /// Verifica existenta evenimentului, locului, pretului
        if (!$id_eveniment || !$loc || $pret === null) {
            throw new Exception("Nu exista evenimentul, locul sau pretul precizat");
        }

        /// Verifica daca locul este intre 1 si 100
        if ($loc < 1 || $loc > 100) {
            throw new Exception("Evenimentul are locuri de la 1 la 100. Nu puteti alege un loc mai mic decat 1, nici mai mare decat 100. ");
        }

        /// Verifica daca locul este ocupat
        $stmt = $db->prepare("select id_bilet from bilet where id_eveniment = ? and loc = ?");
        $stmt->execute([$id_eveniment, $loc]);
        $locOcupat = $stmt->fetch();
        if ($locOcupat) {
            throw new Exception("Locul este ocupat. ");
        }

        /// Insereaza biletul in baza de date
        $stmt = $db->prepare("
            insert into bilet (pret, stare, id_spectator, id_eveniment, loc) values (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$pret, "cumparat", $_SESSION['id_utilizator'], $id_eveniment, $loc]);
        $id_bilet = $db->lastInsertId();
    }

    /// Selecteaza toate informatiile necesare pdf-ului
    $stmt = $db->prepare("
        select 
            b.id_bilet as id_bilet, b.id_spectator as id_spectator, b.loc as loc, b.pret as pret,
            e.denumire as denumire_eveniment, 
            l.denumire as denumire_locatie, 
            j.denumire as denumire_judet, 
            t.denumire as denumire_tara, 
            i.incepe as incepe, i.termina as termina, 
            u_organizator.nume as nume_organizator,
            u_spectator.nume as nume_spectator
        from bilet b
        join eveniment e on e.id_eveniment = b.id_eveniment
        join spectator s on s.id_utilizator = b.id_spectator
        join utilizator u_organizator on e.id_utilizator = u_organizator.id_utilizator
        join utilizator u_spectator on b.id_spectator = u_spectator.id_utilizator
        left join eveniment_istoric e_i on e_i.id_eveniment = e.id_eveniment
        left join istoric i on i.id_istoric = e_i.id_istoric
        left join eveniment_locatie e_l on e_l.id_eveniment = e.id_eveniment
        left join locatie l on l.id_locatie = e_l.id_locatie
        left join judet j on j.id_judet = l.id_judet
        left join tara t on t.id_tara = j.id_tara
        where b.id_bilet = ?
    ");
    $stmt->execute([$id_bilet]);
    $bilet = $stmt->fetch(PDO::FETCH_ASSOC);

    /// Verifica ca biletul sa apartina utilizatorului
    if ($bilet['id_spectator'] != $_SESSION['id_utilizator']) {
        throw new Exception("Nu puteti vedea un bilet care nu este al dumneavoastra");
    }

    /// Genereaza pdf
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 10);
    $pdf->SetFont('Arial', '', 16);

    /// Sectiunea 1: Logo, titlu
    $pdf->Image('../assets/favicon/apple-touch-icon.png', 10, 10, 24, 24); /// cale, x, y, latime, inaltime
    $pdf->SetXY(37, 11);
    $pdf->SetFont('Arial', '', 90);

    $pdf->Cell(0, 24, "Bilet #" . $bilet['id_bilet'], 0, 1); /// lungime, inaltime, text, fara bordura, urmatorul rand

    /// Sectiunea 2: Despre eveniment
    $pdf->SetXY(10, 40);
    $pdf->SetFont('Arial', '', 20);

    $pdf->Cell(50, 12, "Eveniment: ", 1, 0); /// lungime, inaltime, text, bordura, acelasi rand
    $pdf->MultiCell(0, 12, $bilet['denumire_eveniment'], 1, 'L'); /// permite scrierea pe mai multe randuri in cazul unor denumiri mai lungi
    
    $pdf->Cell(50, 12, "Locatie: ", 1, 0); 
    $pdf->MultiCell(0, 12, $bilet['denumire_locatie'] . ', ' . $bilet['denumire_judet'] . ', ' . $bilet['denumire_tara'], 1, 'L'); 

    $pdf->Cell(50, 12, "Incepe: ", 1, 0);
    $pdf->Cell(0, 12, $bilet['incepe'], 1, 1);

    $pdf->Cell(50, 12, "Se termina: ", 1, 0);
    $pdf->Cell(0, 12, $bilet['termina'], 1, 1);

    /// Sectiunea 3: Numele, locul si pretul
    $pdf->SetXY(10, 110);

    $pdf->Cell(95, 10, "Organizator:", 0, 0, 'L');
    $pdf->Cell(95, 10, "Spectator:", 0, 1, 'R');

    $pdf->Cell(95, 10, $bilet['nume_organizator'], 0, 0, 'L');
    $pdf->Cell(95, 10, $bilet['nume_spectator'], 0, 1, 'R');

    $pdf->SetXY(10, 150);
    
    $pdf->Cell(95, 10, "Loc:", 0, 0, 'L');
    $pdf->Cell(95, 10, "Pret:", 0, 1, 'R');

    $pdf->Cell(95, 10, $bilet['loc'], 0, 0, 'L');
    $pdf->Cell(95, 10, $bilet['pret'], 0, 1, 'R');

    /// Seteaza titlul
    $pdf->setTitle('IND | DAW Bilet #' . $id_bilet);

    /// Afiseaza documentul direct in browser
    $pdf->Output('I', 'bilet-' . $id_bilet . '.pdf');
    exit;
} catch (Exception $e) {
    $eroare = $e;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IND | DAW Alege locul</title>
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
        <h1>Alege locul</h1>
        <?php
            if ($eroare) {
                echo "Eroare: " . $eroare->getMessage();
            } else {
                echo "Biletul a fost cumparat cu succes";
            }
        ?>
        <a href="./">Vezi biletele tale</a>
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
