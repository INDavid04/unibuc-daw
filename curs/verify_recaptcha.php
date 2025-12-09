<?php 

/// reCAPTCHA1: https://www.google.com/recaptcha/admin/site/740695923/setup
/// reCAPTCHA2: https://www.google.com/recaptcha/admin/site/740698545/setup

$returnMsg = ''; 
 
if(isset($_POST['submit'])){ 
    
	// Form fields validation check
    if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])){ 
         
        // reCAPTCHA checkbox validation
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 
            // Google reCAPTCHA API secret key 
            $secret_key = '6LexKSYsAAAAAKI7sYq92qBMzry5pLS1-JZaqVFh'; 
             
            // reCAPTCHA response verification
            $verify_captcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']); 
            
            // Decode reCAPTCHA response 
            $verify_response = json_decode($verify_captcha); 
             
            // Check if reCAPTCHA response returns success 
            if($verify_response->success){ 
                
                $name = $_POST['name']; 
                $email = $_POST['email']; 
                $phone = $_POST['phone'];
				$message = $_POST['content'];
             
                #email Gmail
				require_once('./mail/class.phpmailer.php');
				require_once('./mail/mail_config.php');
				
				$mailBody = "User Name: " . $name . "\n";
				$mailBody .= "User Email: " . $email . "\n";
				$mailBody .= "Phone: " . $phone . "\n";
				$mailBody .= "Message: " . $message . "\n";
				
				$mail = new PHPMailer(true); 

				$mail->IsSMTP();

				try {
				 
				  $mail->SMTPDebug  = 3;                     
				  $mail->SMTPAuth   = true; 

				  $toEmail='contact11@dirimia.daw.ssmr.ro';
				  $nume='DAW Project';

				  $mail->SMTPSecure = "tls";                 
				  $mail->Host       = "mail.dirimia.daw.ssmr.ro";      
				  $mail->Port       = 587;                   
				  $mail->Username   = $username;  			// GMAIL username
				  $mail->Password   = $password;            // GMAIL password
				  $mail->AddReplyTo($email, $name);
				  $mail->AddAddress($toEmail, $nume);
				  $mail->addCustomHeader("BCC: ".$email);
				 
				  $mail->SetFrom($username, 'Formular Contact');
				  $mail->From = $username;
				  $mail->Subject = 'Formular contact';
				  $mail->AltBody = 'To view this post you need a compatible HTML viewer!'; 
				  $mail->MsgHTML($mailBody);
                  
				  $mail->Send();
				  
                  $returnMsg = 'Your message has been submitted successfully.'; 
				  
                }
                 catch (phpmailerException $e) {
					echo $e->errorMessage(); //error from PHPMailer
				}
				 
            } 
        }
		  else{ 
            
			$returnMsg = 'Please check the CAPTCHA box.'; 
        } 
    }
	 else
			{ 
				$returnMsg = 'Please fill all the required fields.'; 
			} 
} 
echo $returnMsg;
?>