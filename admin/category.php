<?php
require (__DIR__.'/lib/common.php');
    
if(!admin())
{
    header ("location: login.php");
}
    
require (__DIR__.'/inc/header.php');
require (__DIR__.'/inc/menu.php');   
if(empty($_GET['cat']))
{
	$category = 0;
}
else
{
	$category = $_GET['cat'];
}
    
if(isset($_GET['declineid']))
{
    decline_listing($_GET['declineid']);
}
?>

    <div class='col-12'>

    <h1>Listings for <?php echo categoryName($category); ?></h1> 

    <div id="my-tab-content" class="tab-content">

	<div class="tab-pane active" id="recent">
	    <?php recent_listings($category); ?>
	</div>
    </div>


    </div>
</div>

<script type="text/javascript">

function makesure() {
    if (confirm("Are you sure you wish to remove this listing?")) {
        return true;
    }
    else {
        return false;
    }
}
</script>