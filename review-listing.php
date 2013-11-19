<?php
session_start();
require (__DIR__.'/lib/common.php');
require(__DIR__.'/inc/blocks/header.php');
require(__DIR__.'/inc/blocks/menu.php');
    

if($_GET['id_site'])
{
    $sql = ("
	SELECT  nm_site_name,
		url_site_address,
		tx_site_description,
		tx_site_image,
		int_category 
	FROM pending_listing
	WHERE id_site = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($_GET['id_site']));
    $result = $stm->fetchAll();
    $SITE = $result[0];
}
else
{
    echo '<h1>Error please go back to the site and submit a listing</h1>';
    exit();
}

?>
<div class='col-12 col-lg-12'>
  <fieldset disabled>
    <legend>Review Your Listing</legend>
    <img src='<?= $SITE['tx_site_image']?>' class='img-responsive' />
    <div class="form-group">
      <input type="text" class="form-control" id="sitename" value="<?= $SITE['nm_site_name'] ?>" placeholder="Site Name" required>
    </div>
    <div class="form-group">
      <input type="url" class="form-control" id="siteurl" value="<?= $SITE['url_site_address'] ?>" placeholder="http://www.domain.com" required>
    </div>
	<div class="form-group">
		<textarea id="sitedescription" name="sitedescription" required maxlength="255" class="form-control" rows="5"><?= $SITE['tx_site_description'] ?></textarea>
        <div class="pull-right" id="textarea_feedback"></div>
        
	</div>
	<label for="disabledInput">Category</label>
		<input type="text" class="form-control" placeholder='<?= categoryName($SITE['int_category']); ?>'><br>
</fieldset>


<?= "<form method='POST' action='place-listing.php?id_site={$_GET['id_site']}'>" ?>
<fieldset>
<ul class='featured well col-12'>
	<div class='col-12'>
			<button type="button" class="btn btn-default btn-large check-button" >
				<span style='font-size:25px;'>Featured listing </span>
			</button>
		<a target='_blank' title=' Visit <?= $SITE['nm_site_name']; ?>' href=" <?= $SITE['url_site_address']; ?> " >
			<h3>
				<?= $SITE['nm_site_name']; ?>
			</h3>
		</a>
		<div class='well' style='width:100%; position:relative; margin:0 auto;'>
			<img src='<?= $SITE['tx_site_image']?>' class='img-responsive' />
			<i style='background:#144b23; bottom:0; right:0; position:absolute;' class='icon-trophy pull-right trophy'></i>
		</div>
		<div class='caption'>
			<p style='font-size:14px;'>
				<?= $SITE['tx_site_description']; ?>
			</p>
			<label class='btn btn-default btn-large check-button'>
				<i class='icon-trophy trophy'></i>	Set featured listing	
                                <input type='checkbox' id='featured' name='featured'>
			</label>
		</div>
	</div>
</ul>
	
<div height="100%" class='col-6 well'>
<h2 style='text-align:center;'>Premium listing</h2>
	<ul class='thumbnails'>
		<li class='col-12 clearfix'>
			<a target='_blank' title='Visit <?= $SITE['nm_site_name']; ?>' href=" <?= $SITE['url_site_address']; ?> ">
				<h4>
					<?= $SITE['nm_site_name']; ?>
				</h4>
			</a>
            <div class='well text-center' style='width:100%; position:relative; margin:0 auto;'>
                <img src='<?= $SITE['tx_site_image']?>' class='img-responsive' />
                <i style='background:#144b23; bottom:0; right:0; position:absolute;' class='icon-star pull-right star'></i>
            </div>
			<div class='clearfix text-center'>
				<p style='font-size:14px;'>
					<?= $SITE['tx_site_description']; ?>
				</p>
				<label class='btn btn-default btn-large check-button'>
					<i class='icon-star star'></i>  Premium Listing	
                                        <input type='checkbox' id='premium' name='premium'>
				</label>
			</div>
		</li>
	</ul>
</div>
<div height="100%" class='col-6 well pull-right'>
<h2 style='text-align:center;'>Standard listing</h2>
	<ul class='listing'>
		<a target='_blank' title='<?= $SITE['nm_site_name']; ?>' href=" <?= $SITE['url_site_address']; ?> ">
			<h4>
				<?= $SITE['nm_site_name']; ?>
			</h4>
		</a>
		<p style='font-size:14px;'>
			<?= $SITE['tx_site_description']; ?>
		</p>
		<label class='btn btn-default btn-large check-button' width="100%">
			<i class='icon-bitcoin bitcoin'></i>  Standard Listing	
                        <input disabled="disabled" value='standard' checked="checked" type='checkbox' name='standard'>		
		</label>
	</ul>
</div>

	
<div class='col-12 well'>
	<button type='submit' name='review' class='btn btn-default btn-large check-button'>
		Submit
	</button>
</fieldset>
</div>		
</form>
			
			
			
	
</div><!--end content-->





<?php
    

require(__DIR__.'/inc/blocks/footer.php');
?>
</div>



<script>
$(document).ready(function() {
                  var text_max = 255;
                  $('#textarea_feedback').html(text_max + ' characters');
                  
                  $('#sitedescription').keyup(function() {
                                       var text_length = $('#sitedescription').val().length;
                                       var text_remaining = text_max - text_length;
                                       
                                       $('#textarea_feedback').html(text_remaining + ' characters');
                                       });
                  });

</script>
</body>