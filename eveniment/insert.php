<?php

session_start();

/// reCAPTCHA3: https://www.google.com/recaptcha/admin/site/741246782/setup
 
if(isset($_POST['submit'])){ 
    
	// Form fields validation check
    if(!empty($_POST['nume']) && !empty($_POST['locatie']) && !empty($_POST['data_eveniment'])){ 
         
        // reCAPTCHA checkbox validation
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 
            // Google reCAPTCHA API secret key 
            $secret_key = '6Lc-hy4sAAAAAFfPwr6j2hMwo_aJVoSgKC2WDVDk'; 
             
            // reCAPTCHA response verification
            $verify_captcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']); 
            
            // Decode reCAPTCHA response 
            $verify_response = json_decode($verify_captcha); 
             
            // Check if reCAPTCHA response returns success 
            if ($verify_response->success) { 
                require_once '../login/database.php';

                /// Doar organizatorul poate adauga evenimente
                if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organizator') {
                    die("Acces interzis!");
                }

                /// Organizatorul poate adauga doar evenimente noi
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // date_default_timezone_get('Europe/Bucharest'); /// Rezolva aparitia mesajului chiar daca e o data valida
                    $data_din_formular = $_POST['data_eveniment'];
                    $data_curenta = date('Y-m-d');
                    if ($data_din_formular < $data_curenta) {
                        die("Ne pare rau insa nu puteti adauga evenimente a caror data este mai veche decat astazi ($data_din_formular a fost inainte de $data_curenta)");
                    } else {
                        $pdo = Database::getInstance()->getConnection();

                        $stmt = $pdo->prepare("INSERT INTO eveniment (nume, locatie, data, idOrganizator) VALUES (?, ?, ?, ?)");

                        $stmt->execute([
                            $_POST['nume'], 
                            $_POST['locatie'], 
                            $_POST['data_eveniment'], 
                            $_SESSION['idOrganizator']
                        ]);

                        header("Location: ../");
                        exit;
                    }
                }
            }
        } else {
            die('Nu s-a bifat reCAPTCHA');
        }
    }
} 
