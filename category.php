<?
require_once(__DIR__.'/lib/common.php');
$cat = $_GET['cat'];
if(!$cat > 0)
{
    header('Location: /directory/index.php');
    exit();
}

require_once(__DIR__.'/inc/blocks/header.php');
require_once(__DIR__.'/inc/blocks/menu.php');
$sql_featured_listing = ("
    SELECT * 
    FROM listing 
    WHERE bool_featured = 1 
    AND bool_approved = 1 
    AND int_category = ? 
    ORDER BY Rand() 
    LIMIT 1");
$featured_stm = $conn->prepare($sql_featured_listing);
$featured_stm->execute(array($cat));
$featured_listings = $featured_stm->fetchAll();


if(!empty($featured_listings))
{
    foreach($featured_listings as $featured_listings_row)
    {
    ?>
	<ul class='featured well col-12'>
	    <div class='col-12'>
		<a target='_blank' title='Visit <?= $featured_listings_row['nm_site_name'] ?>' href='http://<?= $featured_listings_row['url_site_address'] ?>'>
		   <h3> <?= $featured_listings_row['nm_site_name'] ?>   <i class='icon-fixed-width icon-globe'></i></h3>
		</a>
		<center>
		    <img class='img-responsive' src='<?= $featured_listings_row['tx_site_image'] ?>' alt='<?= $featured_listings_row['nm_site_name'] ?>' />
		</center>
		<div class='caption'>
		    <p>
			<?= $featured_listings_row['tx_site_description'] ?>
		    </p>
		    <?= "<a href='listing.php?cat={$featured_listings_row['int_category']}&id={$featured_listings_row['id_site']}'>" ?>
			<span class='btn btn-default btn-large'>
			    Read More
			</span>
		    </a>
		</div>
	    </div>
	</ul>
    <?   
    }
}
else
{
    echo '<p>No premium listings found</p>';
}
require_once(__DIR__.'/inc/blocks/sidebar.php');
?>    

<div class='col-8 col-lg-8 well' style='float:right;'>
<?
$sql_premium_listing = ("SELECT * 
    FROM listing 
    WHERE bool_premium = 1 
    AND bool_approved = 1 
    AND int_category = :category 
    ORDER BY Rand() 
    LIMIT 1");
$premium_stm = $conn->prepare($sql_premium_listing);
$premium_stm->execute(array(':category' => $_GET['cat']));
$premium_listings = $premium_stm->fetchAll();

if(!empty($premium_listings))
{
    foreach($premium_listings as $premium_listings_row)
    {
    ?>
    <ul style='text-align:center; list-style-type:none;' class='thumbnails'>
	<li class='col-12 clearfix'>
	    <a target='_blank' href='<?= $premium_listings_row['url_site_address'] ?>' title='Visit <?=$premium_listings_row['url_site_address']?>'>
	       <h3> 
		    <?=$premium_listings_row['nm_site_name'] ?> <i class='icon-fixed-width icon-globe'></i>
		</h3>
	    </a>
	    <center>
		<img class='premiumimg img-responsive' src='<?=$premium_listings_row['tx_site_image']?>' alt='<?=$premium_listings_row['nm_site_name']?>' />
	    </center>
	    <div class='clearfix text-center'>
		<p>
		    <?=$premium_listings_row['tx_site_description']?>
		</p>
		<p align='right'>
		    <a href='listing.php?cat=<?= $cat ?>&id=<?=$premium_listings_row['id_site']?>'>Read More</a>
		</p>
	    </div>
	<hr>
	</li>
    </ul>
    <?
    }
}
 else
{
     echo '<p>No premium listings found</p>';
}

$sql_standard_listing = ("
    SELECT * 
    FROM listing 
    WHERE bool_approved = 1 
    AND int_category = ? 
    ORDER BY Rand() 
    LIMIT 10");
$stm = $conn->prepare($sql_standard_listing);
$stm->execute(array($cat));
$standard_listings = $stm->fetchAll();

if(!empty($standard_listings))
{
    foreach($standard_listings as $row_standard_listings)
    {
    ?>
	<div class='row'>
	    <center>
		<div class='col-8'>
		    <a target='_blank' href='<?=$row_standard_listings['url_site_address'] ?>'>
			<h4>
			    <strong>
				<?= $row_standard_listings['nm_site_name'] ?>
			    </strong>
			</h4>
		    </a>
		    <div class='row'>
			<div class='col-9'>
			    <p>
				<?= $row_standard_listings['tx_site_description'] ?>
			    </p>
			</div>
		    </div>
		<p>
		    <a href='listing.php?cat=<?= $cat ?>&id=<?=$premium_listings_row['id_site']?>'>Read More</a>
		</p>
		</div>
	    </center>
	</div>
    </div>
    <?
    }
}
else
{
    echo '<p>No listings found</p>';
}
?>


</div><!--end content-->






</div>
</body>