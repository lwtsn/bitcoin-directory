<?php
require ('lib/common.php');
    
if(!admin())
{
    header ("location: login.php");
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

if(isset($_POST['insert_cat']))
{
    insert_category($_POST['category'],$category);
}	
	
if(isset($_GET['deletecat']))
{
    delete_category($_GET['deletecat']);
}
?>

<div class='col-12'>


<h1>Categories</h1>

<?php
    $sql = ("SELECT * FROM Categories WHERE parent_cat = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
    $category = $stm->fetchAll();
    
?>
    <ul class='list-group'>
<? 
    foreach ($category as $row)
    {
?>
        <li class='list-group-item'>
	    <a href='categories.php?cat=<?=$row['cat_id']?>'>
		<?=$row ['cat_name'] ?>
		<span style='padding:5px;'>
		    <?= listing_count($row['cat_id']) ?>
		</span>
	    </a>
	    <span style='font-size:20px;' class='pull-right'>
		    <a href='category.php?cat=<?=$row['cat_id']?>' rel='tooltip' class='tooltips' data-original-title='View category'>
			<i style='margin:5px; color:green;' class='icon-eye-open'></i>
		    </a>
		    <a href='?deletecat=<?=$row['cat_id']?>' onclick='return makesure();' rel='tooltip' class='tooltips' data-original-title='Delete category' >
			<i style='margin:5px; color:red;' class='icon-exclamation-sign'></i>
		    </a>
	    </span>
	</li>
<?
    }
 ?>  
    </ul>
<form method="POST" class="form-horizontal">
	<fieldset>
	<legend>New Category</legend>
		<div class="control-group">
			<label class="control-label">Category</label>
			<div class="controls">
				<input id="category" name="category" style='width:100%;' type="text" placeholder="New Category" class="input-xlarge" required>
			</div>
		</div>
		<br>
		<button name='insert_cat' style='width:100%;' class="btn btn-success">Submit</button>
</fieldset>
</form>



<script>
$(function()
  {
      $(".tooltips").tooltip({placement:'bottom', trigger: 'hover'});
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