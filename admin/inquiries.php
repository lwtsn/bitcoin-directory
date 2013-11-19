<?php
    require ('lib/common.php');
    
    if(!admin()){
        header ("location: login.php");
    }
    
    require ('inc/header.php');
	require ('inc/menu.php');
?>

<div class='col-12'>


<h1>Recent Inquiries</h1>

<?php recent_inquiries(); ?>