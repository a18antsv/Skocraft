<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';

    function ends_with($string, $endOfString) { 
        $length = strlen($endOfString); 
        if($length == 0) return true;  
        return (substr($string, -$length) === $endOfString); 
    }

    function generate_hash() {
        return md5(rand());
    }

    function send_mail($address, $activation_code) {
        $mail = new PHPMailer;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //$mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "skocraft2020@gmail.com";
        $mail->Password = "6J4dDArCAoznuK";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
    
        $mail->setFrom("skocraft2020@gmail.com", "Skocraft");
        $mail->addReplyTo("skocraft2020@gmail.com");
        $mail->addAddress($address);
    
        $mail->isHTML(true);
        $mail->Subject = "Skocraft e-mail verification";
        $mail->msgHTML("
            <div>Hi there,</div>
            <br>
            <div>Thank you for showing interest in Skocraft!</div>
            <br>
            <div>
                <a href='http://localhost/skocraft/activate.php?code=$activation_code'>Click here to verify your e-mail.</a>
            </div>
        ");
        return $mail->send();
    }
?>