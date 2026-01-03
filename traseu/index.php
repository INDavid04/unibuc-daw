<?php

require('../assets/fpdf186/fpdf.php');
require_once '../login/database.php';

$db = Database::getInstance()->getConnection();


///
/// Seteaza timezone Romania (EET/EEST)
///

date_default_timezone_set('Europe/Bucharest');
try {
    $db->exec("SET time_zone = '+02:00'");
} catch (Exception $e) {}

///
/// Inlocuieste diacriticele
///

function eliminaDiacritice($text) {
    $search  = array('ș', 'ț', 'ă', 'î', 'â', 'Ș', 'Ț', 'Ă', 'Î', 'Â');
    $replace = array('s', 't', 'a', 'i', 'a', 'S', 'T', 'A', 'I', 'A');
    return str_replace($search, $replace, $text);
}

///
/// Tradu termenii englezesti
///

function traduceManevra($type) {
    $traductor = [
        'depart' => 'Plecare de pe',
        'turn' => 'Viraj pe',
        'roundabout' => 'Giratie pe',
        'exit roundabout' => 'Iesire giratie pe',
        'new name' => 'Continuare pe',
        'merge' => 'Intrare pe',
        'off ramp' => 'Iesire autostrada pe',
        'continue' => 'Inainte pe',
        'arrive' => 'Sosire la',
        'fork' => 'Bifurcatie pe'
    ];
    return $traductor[$type] ?? 'Inainte pe';
}

///
/// Descarca imaginea cu traseul
///

function descarcaImagine($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: image/png,image/jpeg,image/*',
        'Accept-Language: ro-RO,ro;q=0.9,en;q=0.8'
    ]);
    
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    
    curl_close($ch);
    
    /// Verifica raspunsul
    if ($httpCode !== 200 || !$data || strlen($data) < 100) {
        return false;
    }
    
    /// Detecteaza tip imagine din magic bytes
    $magicBytes = bin2hex(substr($data, 0, 4));
    
    /// PNG: 89504e47
    if (strpos($magicBytes, '89504e47') === 0) {
        return ['data' => $data, 'ext' => 'png'];
    }
    
    /// JPEG: ffd8ffe0, ffd8ffe1, ffd8ffe2, etc.
    if (strpos($magicBytes, 'ffd8ff') === 0) {
        return ['data' => $data, 'ext' => 'jpg'];
    }
    
    return false;
}

///
/// Cauta o locatie apropiata de cine acceseaza site-ul prin api-ul api.ip2location.io al celor de la iplocation.io
///

$userIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
$geoKey = "4C64404FA4E09D4F6E5668B096F2AA0D";
$geoJson = @file_get_contents("https://api.ip2location.io/?key=$geoKey&ip=$userIp");
$userData = $geoJson ? json_decode($geoJson, true) : null;

$latUser = $userData['latitude'] ?? 0;
$lonUser = $userData['longitude'] ?? 0;
$orasUser = eliminaDiacritice($userData['city_name'] ?? 'Locatie nedetectata');

///
/// Selecteaza toate locatiile evenimentului
///

$id_eveniment = isset($_GET['id_eveniment']) ? (int)$_GET['id_eveniment'] : 0;
$stmt = $db->prepare("
    select 
        e.denumire as denumire_eveniment, 
        l.denumire as denumire_locatie,
        l.latitudine, 
        l.longitudine
    from eveniment e
    join eveniment_locatie e_l on e.id_eveniment = e_l.id_eveniment
    join locatie l on l.id_locatie = e_l.id_locatie
    where e.id_eveniment = ?
");
$stmt->execute([$id_eveniment]);
$locatii = $stmt->fetchAll(PDO::FETCH_ASSOC);

///
/// Genereaza pdf
///

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(10, 10);

if (file_exists('../assets/favicon/apple-touch-icon.png')) {
    $pdf->Image('../assets/favicon/apple-touch-icon.png', 10, 10, 9, 9);
}

$pdf->SetXY(24, 10);
$pdf->SetFont('Arial', 'B', 30);

$nume_ev = eliminaDiacritice($locatii[0]['denumire_eveniment']);
$pdf->Cell(0, 10, "Traseu - $nume_ev", 0, 1);
$pdf->Ln(5);

if (!empty($locatii)) {
    $pdf->SetFont('Arial', '', 12);
    
    $pdf->Cell(0, 10, "Zona apropiata: $orasUser ($latUser, $lonUser)", 0, 1);
    $pdf->Ln(5);

    $geoapifyKey = "9cfc55ed176a4c3d95be953bcc66853d";

    foreach($locatii as $index => $loc) {
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "Locatie eveniment: " . eliminaDiacritice($loc['denumire_locatie']), 0, 1);
        
        if ($loc['latitudine'] && $loc['longitudine']) {
            /// Calculeaza centrul hartii
            $centerLon = ($lonUser + $loc['longitudine']) / 2;
            $centerLat = ($latUser + $loc['latitudine']) / 2;
            
            // Calculeaza distanta Haversine (km) pentru zoom dinamic
            $earthRadius = 6371; // km
            $dLat = deg2rad($loc['latitudine'] - $latUser);
            $dLon = deg2rad($loc['longitudine'] - $lonUser);
            $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latUser)) * cos(deg2rad($loc['latitudine'])) * sin($dLon/2) * sin($dLon/2);
            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
            $distanceKm = $earthRadius * $c;

            /// Calculeaza zoom in functie de distanta intre cele doua puncte
            $deltaLat = abs($latUser - $loc['latitudine']);
            $deltaLon = abs($lonUser - $loc['longitudine']);
            $maxDelta = max($deltaLat, $deltaLon);
            if ($distanceKm < 1) {
                $zoom = 15;
            } else if ($distanceKm < 5) {
                $zoom = 14;
            } else if ($distanceKm < 20) {
                $zoom = 13;
            } else if ($distanceKm < 50) {
                $zoom = 12;
            } else if ($distanceKm < 100) {
                $zoom = 11;
            } else if ($distanceKm < 200) {
                $zoom = 10;
            } else {
                $zoom = 5;
            }
            
            $mapUrl = 
                "https://maps.geoapify.com/v1/staticmap" . 
                "?style=osm-bright" . 
                "&width=600&height=400" . 
                "&center=lonlat:{$centerLon},{$centerLat}" . 
                "&zoom={$zoom}" .
                "&marker=lonlat:{$lonUser},{$latUser};type:material;color:%230066ff;size:medium;icon:letter_a;iconsize:small" . 
                "|lonlat:{$loc['longitudine']},{$loc['latitudine']};type:material;color:%23ff0000;size:medium;icon:letter_b;iconsize:small" . 
                "&apiKey={$geoapifyKey}";

            $imgResult = descarcaImagine($mapUrl);
            
            if ($imgResult && isset($imgResult['data']) && isset($imgResult['ext'])) {
                $tmpFile = sys_get_temp_dir() . '/harta_' . time() . '_' . $index . '_' . mt_rand() . '.' . $imgResult['ext'];
                
                if (file_put_contents($tmpFile, $imgResult['data'])) {
                    /// Verifica imaginea
                    if (file_exists($tmpFile) && filesize($tmpFile) > 1000) {
                        try {
                            $pdf->Image($tmpFile, 10, $pdf->GetY() + 2, 190, 90);
                            $pdf->Ln(105);
                        } catch (Exception $e) {
                            $pdf->Cell(0, 10, "(Eroare la adaugare harta in PDF)", 0, 1);
                            $pdf->Ln(5);
                        }
                        @unlink($tmpFile);
                    } else {
                        $pdf->Cell(0, 10, "(Fisier harta corupt)", 0, 1);
                        $pdf->Ln(5);
                    }
                } else {
                    $pdf->Cell(0, 10, "(Nu s-a putut salva harta temporar)", 0, 1);
                    $pdf->Ln(5);
                }
            } else {
                $pdf->Cell(0, 10, "(Harta momentan indisponibila - Va rugam reincercati)", 0, 1);
                $pdf->Ln(5);
            }

            /// Scrie si traseul sub forma de instructiuni tip text cu ajutorul osrm api
            $osrmApi = "http://router.project-osrm.org/route/v1/driving/{$lonUser},{$latUser};{$loc['longitudine']},{$loc['latitudine']}?overview=false&steps=true";
            $routeJson = @file_get_contents($osrmApi);
            $routeData = $routeJson ? json_decode($routeJson, true) : null;

            if (isset($routeData['routes'][0]['legs'][0]['steps'])) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 10, "Indicatii:", 0, 1);
                foreach ($routeData['routes'][0]['legs'][0]['steps'] as $step) {
                    $manevra = traduceManevra($step['maneuver']['type']);
                    $strada = ($step['name'] == "" || $step['name'] == "drum") ? "segment drum" : $step['name'];
                    $dist_txt = ($step['distance'] >= 1000) ? round($step['distance']/1000, 1) . " km" : round($step['distance']) . " m";
                    $pdf->MultiCell(0, 5, eliminaDiacritice("- $manevra $strada ($dist_txt)"), 0, 'L');
                }
            }
        }
        $pdf->Ln(10);
    }
}

///
/// Salveaza id_eveniment, ip_vizitator, oras in tabelul traseu pentru analytics
///

try {
    $stmt_log = $db->prepare("insert into traseu (id_eveniment, ip_vizitator, oras) VALUES (?, ?, ?)");
    $stmt_log->execute([$id_eveniment, $userIp, $orasUser]);
} catch (Exception $e) { }

/// Seteaza titlul
$pdf->setTitle('IND | DAW Traseu Eveniment #' . $id_eveniment);

/// Afiseaza documentul direct in browser
$pdf->Output('I', 'traseu-eveniment-' . $id_eveniment . '.pdf');
exit;
