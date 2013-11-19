<?
require_once(__DIR__.'/lib/common.php');

$error_message = '';

if(isset($_POST['password']))
{
    if($_POST['password'] == ADMIN_PASSWORD)
    {
       $_SESSION['admin'] = ADMIN_SECRET;
	header('Location: index.php');
	exit;
    }
    else
    {
	$error_message = "Incorrect Password!";
    }
}
?>



<form class="form-horizontal" method="POST">
<fieldset>
    <div id="legend">
        <legend class="">Welcome Admin</legend><hr>
    </div>
    <div class="control-group">
	<p><span style='color:red;'><?= $error_message ?></span></p>
        <label class="control-label" for="password">Please enter your password</label>
            <div class="controls">
                <input type="password" id="password" name="password" placeholder="" class="input-xlarge">
            </div>
    </div>


    <div class="control-group">
        <div class="controls">
            <button class="btn btn-success">Login</button>
        </div>
    </div>
</fieldset>
</form>