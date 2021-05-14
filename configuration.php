<?php 

    //session security
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.trans_id', 0);
    
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'lax');
    ini_set('session.cookie_secure', 1)

?>