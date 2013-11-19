<?
$cat = $_GET['cat'];
if(empty($cat))
{
    $cat = 0;
}
$menu_query = ("
    SELECT cat_id, cat_name, parent_cat
    FROM Categories
    WHERE (cat_id = ? OR parent_cat = ?)");

$stm = $conn->prepare($menu_query);
$stm->execute(array($cat, $cat));

$categories = $stm->fetchAll();

?>
<div class='col-4 col-lg-4 ' style='padding-left:0;'>
    <ul class="nav nav-pills nav-stacked well">
<?  
foreach($categories as $categories_row)
{
    $act = $categories_row['cat_id'] == $cat ? 'active' : '';
    echo"
	<li class={$act}>
	    <a href='category.php?cat={$categories_row['cat_id']}'>
		{$categories_row['cat_name']}
	    </a> 
	  </li>";
		
    if($categories_row['cat_id'] == $cat && $categories_row['cat_id'] > 0)
    {
	$back_link = "<li><a href='category.php?cat={$categories_row['parent_cat']}' title='Go back to previous category'>Back</a></li>";
    }
}
?>	
	<?= $back_link ?>
    </ul>
</div>