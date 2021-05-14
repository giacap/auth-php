<?php 

    require './configuration.php';

    
    //valuta se spostare questo include dentro la funzione del post
    include_once './config/database.php';

    ini_set('display_errors', 0);


    if(isset($_GET['error'])){
        

        if($_GET['error'] === 'invalidcredentials'){
           echo 'Fill in all the fields, please.';
        } 

        if($_GET['error'] == 'invalidusername'){
            echo 'Invalid Username.';
        }

        if($_GET['error'] == 'usernamealreadytaken'){
            echo 'Username already taken. Please choose another username.';
        }

        if($_GET['error'] == 'error'){
            echo 'We are having issues.';
        }

        if($_GET['error'] == 'invalidpassword'){
            echo 'Invalid Password.';
        }

        if($_GET['error'] == 'none'){
            echo 'Account succcessfully Created.';
        }
    }



    if(isset($_POST['submit'])){
        
        //connect to DB
        $database = new Database();
        $conn = $database->connect();


        //get user input
        $inputUsername = trim($_POST['username']);
        $inputPassword = $_POST['password'];

        //check for empty fields
        if(empty($inputUsername) || empty($inputPassword)){
            header('Location: ./register.php?error=invalidcredentials');
            exit();
        }

        //check for invalid username
        if(!ctype_alnum($inputUsername) || strlen($inputUsername) > 150 || strlen($inputUsername) < 5){
            header('Location: ./register.php?error=invalidusername');
            exit();
        }

        //check for 

        //check if username is already taken
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        if(!$stmt){
            header('Location: ./register.php?error=error');
            exit();
        }
        $stmt->bind_param('s', $name);
        $name = $inputUsername;
        if(!$stmt->execute()){
            header('Location: ./register.php?error=error');
            exit();
        }
        $stmt->store_result();
        if($stmt->num_rows > 0){
            header('Location: ./register.php?error=usernamealreadytaken');
            exit();
        }
        $stmt->close();
        

        //validate pwd characters
        if(strlen($inputPassword) > 150 || strlen($inputPassword) < 5){
            header('Location: ./register.php?error=invalidpassword');
            exit();
        }
        if(!preg_match("/^[a-zA-Z0-9_\-\$@#!]*$/", $inputPassword)){
            header('Location: ./register.php?error=invalidpassword');
            exit();
        }

        //hash pwd and create new user in DB
        {/* 
            eventuale altra validazione input, per validare gli input come variabili di tipo stringa
            $inputUsername = filter_var($inputUsername, FILTER_SANITIZE_STRING);
            $inputPassword = filter_var($inputPassword, FILTER_SANITIZE_STRING);
        */}
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        if(!$stmt){
            header('Location: ./register.php?error=error');
            exit();
        }
        
        $hashedPwd = password_hash($inputPassword, PASSWORD_DEFAULT);
        $stmt->bind_param('ss', $name, $pwd);
        $name = $inputUsername;
        $pwd = $hashedPwd;
        if(!$stmt->execute()){
            header('Location: ./register.php?error=error');
            exit();
        }
        $stmt->close();
        header('Location: ./register.php?error=none');
        exit();
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    
    <h2>REGISTER</h2>
    <div>
        <form action="./register.php" method='POST'>
            <input type="text" name='username' placeholder='enter username'>
            <input type="password" name='password' placeholder='password'>
            <button type='submit' name='submit'>REGISTER</input>
        </form>
    </div>
    <br />
    <br />
    <a href='./'>HOME</a>
</body>
</html>