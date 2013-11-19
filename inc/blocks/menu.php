<div class="navbar navbar-default">
    <ul class="nav navbar-nav">
	<li><a href='<?= SITE_ROOT ?>'>Home</a></li>	
<?php
$cat = $_GET['cat'];
    if($cat > 0)
    {
	echo "<li><a href='submit-listing.php?cat={$cat}'>Submit Listing</a></li>";
    }
    else
    {
	echo "<li><a href='#' id='submitlisting' rel='tooltip' data-original-title='Please navigate to your chosen category to submit a listing'>Submit Listing</a></li>";
    }
?>
	<li><a href="contact.php">Contact us</a></li>
    </ul>
</div>