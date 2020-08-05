<?php
    include "db.php";
    include "functions.php";
    $conn = new DB();

    $email_suffix = "@student.his.se";
    $email = $_POST["email"];
    if(!ends_with($email, $email_suffix)) {
        $email .= $email_suffix;
    }

    $activation_code = generate_hash();
    $isMailed = send_mail($email, $activation_code);

    if($isMailed) {
        $isUpdated = $conn->prepare_execute(
            "UPDATE user SET user.activation_code = ? WHERE user.email=? LIMIT 1",
            [$activation_code, $email]
        );
    }

    echo json_encode([
        "updated" => $isUpdated,
        "mailed" => $isMailed
    ]);
?>