<?php
require ('lib/common.php');
require('inc/blocks/header.php');
require('inc/blocks/menu.php');
?>
<div class='cointainer'>
<?
require('inc/blocks/sidebar.php');

$sql = ("SELECT * FROM listing WHERE id_site = ? AND bool_approved = 1 LIMIT 1");
$stm = $conn->prepare($sql);
$stm->execute(array($_GET['id']));
$listing = $stm->fetchAll();

foreach($listing as $listing_row)
{
    $site_name		 = $listing_row['nm_site_name'];
    $tx_site_description = $listing_row['tx_site_description'];
    $tx_site_image	 = $listing_row['tx_site_image'];
    $url_site_address	 = $listing_row['url_site_address'];
}
?>
    <div class='col-lg-8 well pull-right'>
	<div class='row'>
	    <center>
		<div class='col-8'>
		    <a target='_blank' href='<?=$url_site_address ?>'>
			<h4>
			    <strong>
				<?= $site_name ?>
			    </strong>
			</h4>
		    </a>
		    <div class='row'>
			<div class='col-12'>
			    <img class='img-responsive' src='<?= $tx_site_image ?>'>
			</div>
			<div class='col-9'>
			    <p>
				<?= $tx_site_description ?>
			    </p>
			</div>
		    </div>
		</div>
	    </center>
	</div>
    </div>
</div>


<?php


require('inc/blocks/footer.php');
?>
</div>
</body>