<?php
require ('lib/common.php');
    
include_once 'securimage/securimage.php';
$securimage = new Securimage();
    
if(isset($_POST['captcha_code'])){
    if ($securimage->check($_POST['captcha_code']) == true) {
        submitMessage($_POST['Question'], $_POST['Message'], $_POST['Name'], $_POST['emailAddress'], $_POST['Website']);
        $success = "<span class='alert alert-success col-12 text-center'>Your support inquiry was sucessfully sent - we will get back to you asap</span><br /><br />";
        $_POST = 0;
    }else{
        $error = "<span class='alert alert-danger'>The security code entered was incorrect.</span><br /><br />";
    }
}
require('inc/blocks/header.php');
require('inc/blocks/menu.php');
require('inc/blocks/sidebar.php');
?>


<div class='col-8 col-lg-8 well' style='float:right;'>

<?PHP if(isset($success)){
    echo $success;
}
?>
<form name='submit-form' method='POST'>
    <fieldset>
        <legend>Contact Us</legend>
        <div class="form-group">
            <label for="Name">Your Name</label>
            <input type="text" class="form-control" id="Name" name="Name" value="<?php if(isset($_POST['Name'])){echo $_POST['Name']; } ?>" placeholder="Name" required>
        </div>
        <div class="form-group">
            <label for="emailAddress">Email Address</label>
            <input type="email" class="form-control" id="emailAddress" name="emailAddress" value="<?php if(isset($_POST['emailAddress'])){echo $_POST['emailAddress']; } ?>" placeholder="example@domain.com" required>
        </div>
        <div class="form-group">
            <label for="Website">Website</label>
            <input type="url" class="form-control" id="Website" name="Website" value="<?php if(isset($_POST['Website'])){echo $_POST['Website']; } ?>" placeholder="http://wwww.example.com" >
        </div>
        <div class="form-group">
            <label for="Question">Question</label>
            <input type="text" class="form-control" id="Question" name="Question" value="<?php if(isset($_POST['Question'])){echo $_POST['Question']; } ?>" placeholder="What is your question?" required>
        </div>
        <div class="form-group">
            <label for="Message">Your Message</label>
            <textarea id="Message" name="Message" maxlength="500" class="form-control" rows="5" required> <?php if(isset($_POST['Message'])){echo $_POST['Message']; } ?> </textarea>
                <div class="pull-right" id="textarea_feedback"></div>
        </div><br>
        <div class="form-group text-center">
            <?php if(isset($error)){   echo $error;     } ?>
            <center><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /></center><br>
            <input type="text" class="form-control" id="captcha_code" name="captcha_code" placeholder="Enter Captcha Code Here" size="10" maxlength="6" /><br>
            <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
        </div>
        <br>
        <br>
        <button type="submit" name="submit" class="btn btn-default btn-block">Submit</button>
    </fieldset>
</form>




<?php


require('inc/blocks/footer.php');
?>
</div>


<script>
$(document).ready(function() {
                  var text_max = 500;
                  $('#textarea_feedback').html(text_max + ' characters remaining');
                  
                  $('#Message').keyup(function() {
                                              var text_length = $('#Message').val().length;
                                              var text_remaining = text_max - text_length;
                                              
                                              $('#textarea_feedback').html(text_remaining + ' characters remaining');
                                              });
                  });
</script>
</body>