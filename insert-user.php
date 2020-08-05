<?php
    include "functions.php";

    include "db.php";
    $conn = new DB();

    $errors = [];
    $fields = [
        "email" => "Enter a proper student username",
        "mojang" => "Enter a proper mojang username."
    ];
    $mailed = false;

    foreach($_POST as $key => $value) {
        if(array_key_exists($key, $fields)) {
            if(trim($_POST[$key]) === "") {
                $errors[$key] = $fields[$key];
            }
        }
    }

    if(empty($errors)) {
        $email_suffix = "@student.his.se";
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $mojang = filter_var($_POST["mojang"], FILTER_SANITIZE_STRING);

        if(!ends_with($email, $email_suffix)) {
            $email .= $email_suffix;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = $email." is not valid.";
        }

        $activation_code = generate_hash();
        $mailed = send_mail($email, $activation_code);

        if($mailed) {
            $conn->prepare_execute(
                "INSERT INTO user(email,mojang,activation_code,active_status) VALUES(?,?,?,?)",
                [$email, $mojang, $activation_code, 0]
            );
        }
    }

    echo json_encode([
        "errors" => $errors,
        "mailed" => $mailed
    ]);

?>