<?php

    require './configuration.php';
    session_start();


    //delete obsolete sessions
    if (!isset($_SESSION['lastactivity']) || (time() - $_SESSION['lastactivity'] > 1800)) {
        session_unset();     
        session_destroy(); 
        die('unauthorized');
    }
    
    

    $_SESSION['lastactivity'] = time(); // update last activity time stamp
    

    
    //create csrf token to put in the form
    $_SESSION['csrf'] = bin2hex(random_bytes(64));



    

    echo 'Welcome, ' . $_SESSION['useruid'];
    
    echo '<br />';
    echo '<br />';
    echo '<br />';

    echo "<a href='./logout.php'>LOG OUT</a>";

    echo '<br />';
    echo '<br />';
    echo '<br />';

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form method='POST' action='./cancella-account.php'>
        <input type="hidden" name='cancella'>
        <input type="hidden" name='csrftoken' value="<?php echo $_SESSION['csrf'] ?>">
        <button type='submit' name='submit'>DELETE ACCOUNT</button>
    </form>
    
</body>
</html>
