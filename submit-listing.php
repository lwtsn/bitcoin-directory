<?php
session_start();
if((isset($_GET['reset']) && $_GET['reset'] == 1) && isset($_SESSION['filename']))
{
	unlink($_SESSION['filename']);
	unset($_SESSION['filename']);
}
require (__DIR__.'/lib/common.php');

include_once 'securimage/securimage.php';
$securimage = new Securimage();

if(isset($_POST['captcha_code']))
{
    if ($securimage->check($_POST['captcha_code']) == true)
    {
            $sql = sprintf("INSERT INTO pending_listing (nm_site_name, url_site_address, tx_site_description, int_category, tx_site_image)
                            VALUES (:nm_site_name, :url_site_address, :tx_site_description, :int_category, :tx_site_image)");
            $stm = $conn->prepare($sql);
            
            $stm->execute(array(
                ':nm_site_name'		=> $_POST['siteName'],
                ':url_site_address'	=> $_POST['siteUrl'],
                ':tx_site_description'  => $_POST['siteDescription'],
		':int_category'		=> category(),
                ':tx_site_image'	=> $_SESSION['filename']));
            
            $id_site = $conn->lastInsertId('id_site');
            header('Location: review-listing.php?id_site='.$id_site );
    }
    else
    {
        $warning = "<span class='alert alert-danger'>The security code entered was incorrect.</span><br /><br />";
    }
}


    
require(__DIR__.'/inc/blocks/header.php');
require(__DIR__.'/inc/blocks/menu.php');
require(__DIR__.'/inc/blocks/sidebar.php');

?>

<div class='col-8 col-lg-8 well' style='float:right;'>

<?php
    require_once (__DIR__.'/lib/image/phUploader.php');
?>
<form name='submit-form' method='POST'>
  <fieldset>
    <legend>Submit Your Website</legend>
    <div class="form-group">
      <label for="siteName">Site Name</label>
	  <input type="text" class="form-control" id="siteName" name="siteName" value="<?=  isset($_POST['siteName']) ? $_POST['siteName'] : '' ?>" placeholder="Site Name" required>
    </div>
    <div class="form-group">
      <label for="siteUrl">Site Address</label>
      <input type="url" class="form-control" id="siteUrl" name="siteUrl" value="<?= isset($_POST['siteUrl']) ? $_POST['siteUrl'] : '' ?>" placeholder="http://www.domain.com" required>
    </div>
	<div class="form-group">
		<label for="siteDescription">Site Description</label>
		<textarea id="siteDescription" name="siteDescription" maxlength="255" class="form-control" rows="5" required><?= isset($_POST['siteUrl']) ? $_POST['siteUrl'] : '' ?> </textarea>
        <div class="pull-right" id="textarea_feedback"></div>
	</div>
	<label for="disabledInput">Category</label>
	<fieldset disabled>
	    <input type="text" name="category" id="category" class="form-control" value='<?= categoryName(category()); ?>' required><br>
		<div class="alert alert-info">Navigate to your chosen category to submit your site</div>
	</fieldset>
    <div class="form-group text-center">
        <?php if(isset($warning)){   echo $warning;     } ?>
        <center><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /></center><br>
        <input type="text" class="form-control" id="captcha_code" name="captcha_code" placeholder="Enter Captcha Code Here" size="10" maxlength="6" /><br>
        <a href="#" style="color:#3a87ad" class="alert alert-info btn-block" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
    </div>
<br>
    <?= "<center><img src='{$_SESSION['filename']}' /></center>" ?>
<br>
	<button type="submit" name="submit" class="btn btn-default btn-block">Submit</button>
    <a href="?reset=1" style="color:white" class="btn btn-danger btn-block">Reset</a>
  </fieldset>
</form>


</div><!--end content-->

<?

require(__DIR__.'/inc/blocks/footer.php');
?>
</div>



<script>
$(document).ready(function() {
                  var text_max = 255;
                  $('#textarea_feedback').html(text_max + ' characters remaining');
                  
                  $('#siteDescription').keyup(function() {
                                       var text_length = $('#siteDescription').val().length;
                                       var text_remaining = text_max - text_length;
                                       
                                       $('#textarea_feedback').html(text_remaining + ' characters remaining');
                                       });
                  });
</script>


</body>