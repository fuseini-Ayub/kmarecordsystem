<?php

function check_login($required_user_type = 2) {
    session_start();
    
    if (isset($_SESSION['user_data'])) {
        if ($_SESSION['user_data']['usertype'] != $required_user_type) {
            header("Location:../admin/index.php");
            exit();
        }
    } else {
        header("Location:../../index.php?error=Unauthorized Access");
        exit();
    }
}

?>