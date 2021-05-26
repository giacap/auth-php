<?php
    
   
    require './configuration.php';
    session_start();
    
    //csrf protection when deleting account
    if($_POST['csrftoken'] !== $_SESSION['csrf']){
        header('Location: ./index.php');
        exit();
    }

    //simulate action of deleting account
    echo 'Account deleted';
?>