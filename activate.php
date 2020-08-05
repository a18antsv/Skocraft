<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
    <div class="page-container">
        <div class="fullscreen-container current">
            <div class="information-container">
                <?php
                    include "db.php";
                    $conn = new DB();

                    if(isset($_GET["code"])) {
                        $activation_code = $_GET["code"];
                        $conn->prepare_execute(
                            "SELECT * FROM user WHERE user.activation_code=? LIMIT 1",
                            [$activation_code]
                        );
                        if($conn->row_count() == 1) {
                            $row = $conn->fetch();
                            if($row["active_status"] == "0") {
                                $isUpdated = $conn->prepare_execute(
                                    "UPDATE user SET user.active_status = 1 WHERE user.activation_code=? LIMIT 1",
                                    [$activation_code]
                                );
                                if($isUpdated) {
                                    echo "<div class='success bold'>Your student username has been verified.</div>";
                                    $email = $row["email"];
                                    $username = substr($email, 0, strpos($email, "@"));
                                    //shell_exec($username);
                                } else {
                                    echo "<div class='error bold'>Your student username could not be verified.</div>";
                                }
                            } else {
                                echo "<div class='error bold'>Your student username is already verified.</div>";
                            }
                        } else {
                            echo "<div class='error bold'>No student username to verify found.</div>";
                        }
                    } else {
                        echo "<div class='error bold'>No verification hash code provided.</div>";
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>