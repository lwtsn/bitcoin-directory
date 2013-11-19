<?php
    require ('lib/common.php');

    session_destroy();

    header ('location: login.php');
    
    exit;

?>