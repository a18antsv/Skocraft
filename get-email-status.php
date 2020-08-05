<?php
    include "db.php";
    include "functions.php";
    $conn = new DB();

    $email = "";
    $email_suffix = "@student.his.se";

    if(isset($_POST["email"])) {
        $email = $_POST["email"];
        if(!ends_with($email, $email_suffix)) {
            $email .= $email_suffix;
        }
    }

    $conn->prepare_execute(
        "SELECT * FROM user WHERE user.email=?",
        [$email]
    );

    echo json_encode(
        [
            "exists" => $conn->row_count() == 1 ? true : false,
            "active" => $conn->fetch()["active_status"] == "1" ? true : false
        ]
    );
?>