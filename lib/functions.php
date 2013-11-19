<?php

/* PAGE LISTINGS */

//////////////////// FEATURED LISTINGS ////////////////
function featured($cat){
global $conn;
    if($cat === "N/A")
    {
        $sql = ("SELECT * FROM listing WHERE bool_featured = 1 AND bool_approved = 1 ORDER BY RAND() LIMIT 1"); 
    }
    else
    {
        $sql = ("SELECT * FROM listing WHERE bool_featured = 1 AND bool_approved = 1 AND int_category = ? ORDER BY RAND() LIMIT 1");
    }
    
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
    $featured = $stm->fetchAll();
    
    
    $loop = "<ul class='featured well col-12'>";
    
    foreach($featured as $row){
        $loop.= "<div class='col-12'>";
        $loop.= "<a target='_blank' title='Visit ".$row['nm_site_name']."' href='http://".$row['url_site_address']."'><h3>".$row['nm_site_name']."   <i class='icon-fixed-width icon-globe'></i></h3></a>";
        $loop.= "<center><img class='img-responsive' src='".$row['tx_site_image']."' alt='".$row['nm_site_name']."' /></center>";
        $loop.= "<div class='caption'> <p>".$row['tx_site_description']."...</p>";
        $loop.= generatelink($row['int_category'],$row['id_site'])."<span class='btn btn-default btn-large'> Read More</span></div></div></a>";
    }
    $loop .="</ul>";
    echo $loop;
}

//////////////////// PREMIUM LISTINGS ////////////////
function premium($cat){
global $conn;
    
    if($cat === "N/A")
	{
        $sql = ("SELECT * FROM listing WHERE bool_premium = 1 AND bool_approved = 1 ORDER BY Rand() LIMIT 1");
    }else
	{
        $sql = ("SELECT * FROM listing WHERE bool_premium = 1 AND bool_approved = 1 AND int_category = ? ORDER BY Rand() LIMIT 1");
    }
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
    $premium = $stm->fetchAll();
	
	$loop =  "<ul style='text-align:center; list-style-type:none;' class='thumbnails'>";
    foreach ($premium as $row){
        $loop.=  "<li class='col-12 clearfix'>";
        $loop.=  "<a target='_blank' title='Visit ".$row['nm_site_name']."' href='http://".$row['url_site_address']."'><h3>".$row['nm_site_name']."   <i class='icon-fixed-width icon-globe'></i></h3></a>";
        $loop.=  "<center><img class='premiumimg img-responsive' src='".$row['tx_site_image']."' alt='".$row['nm_site_name']."' /></center>";
        $loop.=  "<div class='clearfix text-center'>";
        $loop.=  "<p>".$row['tx_site_description']."</p>";
        $loop.=  generatelink($row['int_category'],$row['id_site'])."<p align='right'>Read More...</p></a></div><hr></li></ul>";
    }
    echo $loop;
}
    
    
//////////////////// STANDARD LISTINGS ////////////////
function standardlisting($cat){
global $conn;
$rows = 10;

    if($cat === "N/A"){
        $sql = ("SELECT COUNT(*) FROM listing WHERE bool_approved = 1");
    }else{
        $sql = ("SELECT COUNT(*) FROM listing WHERE int_category = ? AND bool_approved = 1");
    }
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
    $count = $stm->fetchColumn();
    
    
    $totalpages = ceil($count/$rows);
    
    if(isset($_GET['page'])){
        $currentpage = $_GET['page'];
    }else{
        $currentpage =  1;
    }
    
    $offset = ($currentpage - 1) * $rows;
    
    
    if($cat === "N/A"){
        $sqlListings = ("SELECT * FROM listing WHERE bool_approved = 1 ORDER BY dt_date_listed DESC LIMIT $offset, $rows");
    }else{
        $sqlListings = ("SELECT * FROM listing WHERE int_category = ? AND bool_approved = 1 ORDER BY dt_date_listed DESC LIMIT $offset, $rows");
    }
    $stm = $conn->prepare($sqlListings);
    $stm->execute(array(category()));
    $listing = $stm->fetchAll();

    if($listing){
        $loop = "<ul class='listing'>";
            
        foreach($listing as $row){
            $loop.= "<a target='_blank' title='Visit ".$row['nm_site_name']."' href='http://".$row['url_site_address']."'><h3>".$row['nm_site_name']."   <i class='icon-fixed-width icon-globe'></i></h3></a>";
                
            $loop.= "</a><p>".$row['tx_site_description']."...</p>".generatelink($row['int_category'],$row['id_site'])."<p align='right'>Read More...</p><hr>";
        }
        $loop .="</ul>";
    }else{
        $loop = "There are currently no listings in this category";
    }
    echo $loop;
    pagination($currentpage, $totalpages, $cat);
}
    
    
/* END PAGE LISTINGS */
    
    
///////////////////////////////// PAGINATION /////////////////////////////////
function pagination($currentpage, $totalpages, $cat){
    if ($currentpage > 1){
        $prevpage = $currentpage - 1;
        echo "<a class='btn btn-small btn-primary' style='float:left; width:50px;' href='{$_SERVER['PHP_SELF']}?cat=$cat&page=$prevpage'>BACK</a>";
    }
        
    if ($currentpage < $totalpages){
        $nextpage = $currentpage + 1;
        echo "<a class='btn btn-small btn-primary' style='float:right; width:50px;' href='{$_SERVER['PHP_SELF']}?cat=$cat&page=$nextpage'>NEXT</a> ";
    }
}
   
    
///////////////////////////////// GET CATEGORY /////////////////////////////////
function category(){ //cat not set on index page
    if(empty($_GET['cat']) || $_GET['cat'] === "N/A")
    {
	$cat = 0;
    }
    else
    {
	$cat = $_GET['cat'];
    }
    return $cat;
}
    
//////////////////// LISTING PAGE LISTINGS ////////////////
function listingPage($siteid){
global $conn;
    $sql = ("SELECT * FROM listing WHERE id_site = ? AND bool_approved = 1");
    $stm = $conn->prepare($sql);
    $stm->execute(array($siteid));
    $listing = $stm->fetch();
    
    
    $loop = "<div class='row listing'><div class='col-8'>";
    
    $loop.= "<h4><strong>".$listing['nm_site_name']."</strong></h4>";
    $loop.="<div class='row'><div class='col-12'><img class='img-responsive' src='".$listing['tx_site_image']."'></div>";
    $loop.="<div class='col-9'><p>".$listing['tx_site_description']."</p>";
    $loop.="</div></div></div></div><hr>";
    
    echo $loop;
}
    
    
//////////////////// GENERATE LISTING URL ////////////////
function generatelink($cat,$id){
    return "<a href='listing.php?cat=".$cat."&id=".$id."'>";
}

//////////////////// RETURN CURRENT CATEGORY NAME /////////////////////////////////    
function categoryName($cat){
global $conn;
    $sql = ("SELECT cat_name FROM Categories WHERE cat_id = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
    $cats = $stm->fetch();
    
    return $cats[0];
}
    
    
///////////////////// SELECT SUBCATEGORIES OF CURRENT CATEGORY /////////////////////
function subcategories($cat_name){
global $conn;
    
    $query = ("SELECT * FROM Categories WHERE parent_cat = ?");
    $stm = $conn->prepare($query);
    $stm->execute(array(category()));
    $parent = $stm->fetchAll();
    
    $links = parent();
    
    $links .= "<ul>";
    foreach ($parent as $row){
        $links.= "<li><a href='category.php?cat=".$row['cat_id']."'>".$row['cat_name']."</a></li>";
    }
    
    $links .= "</ul>";
    
    $links .= "<hr><li><a href='category.php?cat=0'>Back</a></li>";

    echo $links;
}
    
///////////////////// CONFIGURE SIDEBAR - IF IN CATEGORY SHOW SUBCATEGORIES /////////////////////
function menu(){
global $conn;

$query = ("SELECT parent_cat, cat_name FROM Categories WHERE cat_id = ?");
$stm = $conn->prepare($query);
$stm->execute(array(category()));
$par_cat = $stm->fetch();
    if(empty($par_cat)||($par_cat == 0)){
        Categories();
    }else{
        subcategories($par_cat['cat_name']);
    }
}
    
///////////////////// SHOW CURRENT SUBCATEGORIES PARENT /////////////////////
function parent(){
global $conn;

    $query = ("SELECT parent_cat, cat_name FROM Categories WHERE cat_id = ?");
    $stm = $conn->prepare($query);
    $stm->execute(array(category()));
    $par_cat = $stm->fetch();
    
    return "<li class='active'><a href='category.php?cat=".$par_cat['parent_cat']."'>".$par_cat['cat_name']."</a></li>";
}
   
    
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
    
function generateRandomNumber($length) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
    
////////////////////////// CONTACT FORM //////////////////////////
function submitMessage($question, $message, $name, $email, $website){
global $conn;
    $query = ("INSERT INTO inquiries (question, message, name, email, website) VALUES (:question, :message, :name, :email, :website)");
    $stm = $conn->prepare($query);
    $stm->execute(array(
        ":question" => $question,
        ":message" => $message,
        ":name" => $name,
        ":email" => $email,
        ":website" => $website,
    ));
}
?>