<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IND | DAW Tema 1: Descrierea aplicatiei web</title>
    <link rel="stylesheet" href="./css/styles.css">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
    <link rel="mask-icon" href="./favicon/safari-pinned-tab.svg" color="#3e433d">
    <meta name="msapplication-TileColor" content="#3e433d">
    <meta name="theme-color" content="#3e433d">
</head>
<body>
    <header>
        <a href="./">
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
                <li><a href="./descrierea-aplicatiei.php">Descrierea aplicatiei</a></li>
                <?php if (!isset($_SESSION['username'])): ?>
                    <li><a href="./login/account.php">Creeaza cont / Autentifica-te</a></li>
                <?php else: ?>
                    <li><a href="./login/user-info.php">Despre <?= htmlspecialchars($_SESSION['username']); ?></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Tema 1: Descrierea aplicatiei web</h1>
        <h2>Ce face aplicatia organizare evenimente?</h2>
        <ol>
            <li>Strange intr-un singur loc evenimentele organizate de o companie</li>
            <li>Ofera posibilitatea de a vedea organizarea evenimentelor si a altor companii</li>
        </ol>

        <h2>Ce roluri are aplicatia organizare evenimente?</h2>
        <ol>
            <li>Tine o evidenta clara a ordinii desfasurarii a mai multor evenimente</li>
            <li>Ajuta la promovarea evenimentelor</li>
            <li>Creste publicul, intrucat acesta va sti exact unde si cand se vor tine evenimentele</li>
        </ol>
        
        <h2>Cum e structurata aplicatia organizare evenimente?</h2>
        <ol>
            <li>Utilizatorul obisnuit intra pe website</li>
            <li>
                <span>Isi poate face cont fie de</span>
                <ol>
                    <li>Specatator, caz in care poate salva anumite preferinte, la fel ca pe youtube, ce sa apara pe prima pagina la feed</li>
                    <li>Organizator, caz in care poate crea evenimente, grupandu-le pe maratoane</li>
                </ol>
            </li>
        </ol>

        <h2>Cine foloseste aplicatia organizare evenimente?</h2>
        <ol>
            <li>Specatorii pot folosi aplicatia pentru a sti la ce evenimente le permite timpul sa participe</li>
            <li>Organizatorii pot folosi aplicatia pentru a oferi spectatorilor detatlii concrete cu privire la evenimentele ce vor avea loc in viitor sau care au avut loc deja</li>
        </ol>
        
        <h2>Ce contine diagrama ERD a aplicatiei organizare evenimente?</h2>
        <ul>
            <li>
                <span>Baza de date este formata din sapte entitati principale:</span>
                <ul>
                    <li>spectator: pk id_spectator</li>
                    <li>organizator: pk id_organizator</li>
                    <li>eveniment: pk id_eveniment fk id_organizator</li>
                    <li>bilet: pk id_bilet, fk id_spectator, fk id_eveniment</li>
                    <li>newsletter: pk id_newsletter, fk id_spectator</li>
                    <li>review: pk id_review, fk id_spectator, fk id_eveniment, fk id_bilet</li>
                    <li>preferinta: pk id_preferinta, fk id_spectator</li>
                </ul>
            </li>
            <li>
                <span>Baza de date contine urmatoarele relatii, pentru:</span>
                <h3>Spectator</h3>
                <ul>
                    <li>Un spectator poate participa la unul sau la mai multe evenimente</li>
                    <li>Un spectator poate detine unul sau mai multe bilete</li>
                    <li>Un spectator poate fi abonat la zero sau la un singur newsletter</li>
                    <li>Un spectator poate oferi unul sau mai multe review-uri</li>
                    <li>Un spectator poate avea una sau mai multe preferinte</li>
                </ul>
                <h3>Organizator</h3>
                <ul>
                    <li>Un organizator se poate ocupa de unul sau de mai multe evenimente</li>
                </ul>
                <h3>Eveniment</h3>
                <ul>
                    <li>Un eveniment poate avea unul sau mai multi spectatori</li>
                    <li>Un eveniment poate fi organizat de unul si numai un singur organizator</li>
                </ul>
                <h3>Bilet</h3>
                <ul>
                    <li>Un bilet poate fi detinut de unul si numai un singur spectator</li>
                </ul>
                <h3>Newsletter</h3>
                <ul>
                    <li>Un newsletter se poate regasi la unul sau la mai multi spectatori</li>
                </ul>
                <h3>Review</h3>
                <ul>
                    <li>Un review poate fi acordat de unul si numai un singur spectator</li>
                </ul>
                <h3>Preferinta</h3>
                <ul>
                    <li>O preferinta poate fi regasita la unul sau la mai multi spectatori</li>
                </ul>
            </li>
        </ul>

        <h2>Care-i flow-ul (scenariile de utilizare) ale aplicatiei organizare evenimente?</h2>
        <ul>
            <li>
                <span>Pasi in logare:</span>
                <ol>
                    <li>Utilizator neautentificat</li>
                    <li>Utilizatorul neautentificat vede evenimentele intr-o ordine aleatoare</li>
                    <li>
                        <span>Spectatorul se poate autentifica fie ca</span>
                        <ol>
                            <li>Spectator, caz in care poate seta ce evenimente sa vada in feed</li>
                            <li>Organizator, caz in care poate adauga evenimente</li>
                        </ol>
                    </li>
                </ol>
            </li>
            <li>
                <span>Pasi in cumparare bilet:</span>
                <ol>
                    <li>User logat</li>
                    <li>Alege eveniment</li>
                    <li>Alege tipul de bilet</li>
                    <li>Verifica datele</li>
                    <li>Cumpara</li>
                </ol>
            </li>
            <li>
                <span>Pasi in abonare la newsletter:</span>
                <ol>
                    <li>Daca e logat, cerem aprobare</li>
                    <li>Daca nu e logat, cerem email</li>
                </ol>
            </li>
        </ul>

        <h2>Ce arhitectura foloseste aplicatia organizare evenimente?</h2>
        <ol>
            <li>Frontend: HTML, CSS(SCSS), JavaScript</li>
            <li>Backend: PHP</li>
            <li>Baza de date: MySQL</li>
        </ol>

        <h2>Diagrama ERD</h2>
        <iframe width="768" height="432" src="https://miro.com/app/live-embed/uXjVJtrXI50=/?embedMode=view_only_without_ui&moveToViewport=289,0,720,720&embedId=820711263552" frameborder="0" scrolling="no" allow="fullscreen; clipboard-read; clipboard-write" allowfullscreen></iframe>

        <h2>Flow autentificare</h2>
        <iframe width="768" height="432" src="https://miro.com/app/live-embed/uXjVJtsJ2vI=/?embedMode=view_only_without_ui&moveToViewport=-1571,-632,3547,1956&embedId=399224724897" frameborder="0" scrolling="no" allow="fullscreen; clipboard-read; clipboard-write" allowfullscreen></iframe>

        <h2>Flow cumparare bilet</h2>
        <iframe width="768" height="432" src="https://miro.com/app/live-embed/uXjVJtscDHI=/?embedMode=view_only_without_ui&moveToViewport=-902,-529,2083,1148&embedId=566575844091" frameborder="0" scrolling="no" allow="fullscreen; clipboard-read; clipboard-write" allowfullscreen></iframe>

        <h2>Flow abonare la newsletter</h2>
        <iframe width="768" height="432" src="https://miro.com/app/live-embed/uXjVJtzjm5U=/?embedMode=view_only_without_ui&moveToViewport=-1264,-969,1586,875&embedId=889688874562" frameborder="0" scrolling="no" allow="fullscreen; clipboard-read; clipboard-write" allowfullscreen></iframe>
    </main>

    <footer>
        <h2>Mergi catre</h2>
        <ul>
            <li><a href="./">Pagina principala</a></li>
            <li><a href="./descrierea-aplicatiei.php">Descrierea aplicatiei</a></li>
            <li><a href="./autentificare-prin-imagine.php">Tema cu autentificare prin imagine</a></li>
            <li><a href="./generare-document.php">Tema cu generare document</a></li>
        </ul>   
        <div>
            <p>All rights reserved &copy; 2025</p>
            <p>Made with love by <a href="http://indavid04.github.io/portofolio" target="_blank" rel="noopener noreferrer">INDavid04</a></p>
        </div> 
    </footer>
</body>
</html>
