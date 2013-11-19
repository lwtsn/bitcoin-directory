<?php
require ('lib/common.php');
	
if(!admin())
{
    header ("location: login.php");
    exit(1);
}

require ('inc/header.php');
require ('inc/menu.php');
	
if(empty($_GET['cat']))
{
	$category = 0;
}
else
{
	$category = $_GET['cat'];
}
?>

<div class='col-12'>


<h1>Welcome Admin</h1>

<?php
    
    
if(isset($_GET['approveid']))
{
    approve_listing($_GET['approveid']);
}

if(isset($_GET['declineid']))
{
    decline_listing($_GET['declineid']);
}
    
?>

<ul class="nav nav-tabs">
    <li class='active'><a data-toggle="tab" href='#recent'>Recent Listings</a></li>
    <li><a data-toggle="tab" href='#new'>New Listings <?php echo new_listing_count($category); ?></a></li>

</ul>

<div id="my-tab-content" class="tab-content">

    <div class="tab-pane active" id="recent">
        <?php recent_listings(0); ?>
    </div>
    <div class="tab-pane" id="new">
        <?php new_listings(); ?>
    </div>
</div>


</div>






</div>

<script type="text/javascript">
jQuery(document).ready(function ($) {
                       $(".tabs").tabs();
                       });
</script>

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