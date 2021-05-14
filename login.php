<?php 
    
    require './configuration.php';
    session_start();

    //valuta se spostare questo include dentro la funzione del post
    include_once './config/database.php';

    ini_set('display_errors', 1);

    if(isset($_GET['error'])){
        if($_GET['error'] === 'invalidcredentials'){
            echo 'Invalid credentials. Please try again.';
        }
        if($_GET['error'] == 'wrongusername'){
            echo 'Wrong Username. Try again.';
        }
        if($_GET['error'] == 'error'){
            echo 'We are having issues.';
        }
        if($_GET['error'] == 'wrongpwd'){
            echo 'Invalid password. Try again.';
        }
    }




    if(isset($_POST['submit'])){

        $database = new Database();
        $conn = $database->connect();

        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];

        //check for empty fields
        if(empty($inputUsername) || empty($inputPassword)){
            header('Location: ./login.php?error=invalidcredentials');
            exit();
        }

        //check if username is correct
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        if(!$stmt){
            header('Location: ./login.php?error=error');
            exit();
        }
        $stmt->bind_param('s', $name);
        $name = $inputUsername;
        if(!$stmt->execute()){
            header('Location: ./login.php?error=error');
            exit();
        }
        $stmt->store_result();
        if($stmt->num_rows < 1){
            header('Location: ./login.php?error=wrongusername');
            exit();
        }
        
        //check if password is correct
        $stmt->bind_result($id, $username, $password, $created_at);
        $originalPwd;
        $userid;
        $useruid;
        while ($stmt->fetch()){
            $originalPwd = $password;
            $userid = $id;
            $useruid = $username;
        };
        $stmt->close();
        
        $checkPwd = password_verify($inputPassword, $originalPwd);
        if($checkPwd === false){
            header('Location: ./login.php?error=wrongpwd');
            exit();
        } else if ($checkPwd === true){
            //log user in user using session
            //regenerate the session id to mitigate session attack risk
            session_regenerate_id(true);
            $_SESSION['userid'] = $userid;
            $_SESSION['useruid'] = $useruid;
            $_SESSION['lastactivity'] = time();
            header('Location: ./index.php');
            exit();

        }
        

        
        
    
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<body>
    
    <h2>LOG IN</h2>
    <div>
        <form action="./login.php" method='POST'>
            <input type="text" name='username' placeholder='enter username'>
            <input type="password" name='password' placeholder='enter password'>
            <button type='submit' name='submit'>LOG IN</input>
        </form>
    </div>
    <br />
    <br />
    <a href='/php_auth'>HOME</a>
</body>
</html>