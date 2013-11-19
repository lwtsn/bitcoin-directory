<?php
    
//////////////////// GENERATE LISTING URL ////////////////
function generatelink($cat,$id)
{
    return "listing.php?cat=".$cat."&id=".$id;
}
function insert_category($cat, $parent){
global $conn;
	$query = ("INSERT INTO categories (cat_name, parent_cat) VALUES (:cat, :parent)");
    $stm = $conn->prepare($query);
    $stm->execute(array(
	":cat" => $cat,
	":parent" => $parent,
	));
}
    
function listing_count($category){
global $conn;
    
    $sql = ("SELECT count(*) FROM listing WHERE int_category = :category");
    $stm = $conn->prepare($sql);
    $stm->execute(array(
	":category" => $category
    ));
    $category = $stm->fetch();
    
    if(empty($category['0']))
    {
        $category['0'] = 0;
    }
    
    return $category[0];
}
    
function new_listing_count(){
global $conn;
        
    $sql = ("SELECT count(*) FROM listing WHERE bool_approved = 0");
    $stm = $conn->prepare($sql);
    $stm->execute();
    $category = $stm->fetch();
    
    if(empty($category['0']))
    {
        $category['0'] = 0;
    }
    
    $category = "<span style='color:black;'>".$category['0']."</span>";
    
    return $category;  
}
// COLOUR ICONS BASED ON PAYMENT STATUS //
function paid_listing($paid){
    if($paid == 0)
    {
        return "background:#D14334;";
    }
    else
    {
        return "background:#144b23;";
    }
}
    
// GENERATE ICONS BASED ON BOOLEAN VARIABLES //
function generateicons($featured, $premium){
    $loop = "<i style='".paid_listing($featured)."' class='icon-trophy trophy pull-right'></i><i style='".paid_listing($premium)."' class='icon-star trophy pull-right'></i>";
    return $loop;
}
    
function new_listings(){
global $conn;
        
    $sql = ("SELECT * FROM listing WHERE bool_approved = ? ORDER BY dt_date_listed ASC LIMIT 20");
    $stm = $conn->prepare($sql);
    $stm->execute(array(0));
    $listings = $stm->fetchAll();
	
    $loop = "<ul class='well'>";
    if(empty($listings))
    {
        $loop .= "No new listings found";
    }
    else
    {
	foreach ($listings as $row)
	{
		$loop.= "<center><a target='_blank' title='Visit ".$row['nm_site_name']."' href='http://".$row['url_site_address']."'><h5>".$row['nm_site_name']."   <i class='icon-fixed-width globe icon-globe'></i></h5></a>";
		$loop.= generateicons($row['bool_featured'], $row['bool_premium']);
		$loop.= "<img class='img-responsive' src='../".$row['siteimage']."' /></center></a>";
		$loop.= "<p>".$row['tx_site_description']."</p><br><br>";
		$loop.= "<div class='col-12'><a class='btn btn-success col-6' style='display:inline;' href='?approveid=".$row['id_site']."'>Accept</a>";
		$loop.= "<a onclick='return makesure();' class='btn btn-danger col-6 pull-right' style='display:inline;' href='?declineid=".$row['id_site']."'>Decline</a></div>";
		$loop.= "<div class='clearfix'></div><hr>";
	}
    }
	$loop .= "</ul>";
        echo $loop;
}
    
function recent_listings($category){
global $conn;
        
    if($category == 0){
		$sql = ("SELECT * FROM listing ORDER BY dt_date_listed DESC LIMIT 10");
	}else{
		$sql = $sql = ("SELECT * FROM listing WHERE int_category = :category ORDER BY dt_date_listed DESC LIMIT 10");
	}
    $stm = $conn->prepare($sql);
    $stm->execute(array(
	":category" => $category,
	));
    $listings = $stm->fetchAll();
	 
    $loop = "<ul class='well'>";
	if(!empty($listings))
	{       
		foreach ($listings as $row)
		{
			$loop.= "<center><a target='_blank' title='Visit ".$row['nm_site_name']."' href='http://".$row['url_site_address']."'><h5>".$row['nm_site_name']."   <i class='icon-fixed-width globe icon-globe'></i></h5></a>";
			$loop.= generateicons($row['bool_featured'], $row['bool_premium']);
			$loop.= "<img src='../".$row['tx_site_image']."' class='img-responsive' /></center></a>";
			$loop.= "<p>".$row['tx_site_description']."</p><br><br>";
			$loop.= "<p><a class='btn btn-success view-listing' style='text-align:center; width:100%;' target='_blank' href='../".generatelink($row['int_category'],$row['id_site'])."'>View listing</a></p>";
			$loop.= "<p><a class='btn btn-danger view-listing'  style='text-align:center; width:100%;' onclick='return makesure();' href='?cat=".$category."&declineid=".$row['id_site']."'>Remove Listing</a></p>";
			$loop.= "<div class='clearfix'></div><hr>";
		}
	}
	else
	{
		$loop .= 'No listings found';
	}
	$loop .= "</ul>";
    echo $loop;
}

    
function approve_listing($id){
global $conn;
    $sql = ("UPDATE listing SET bool_approved = 1 WHERE id_site = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($id));
}


function decline_listing($id){
global $conn;
    $sql = ("DELETE FROM listing WHERE id_site = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($id));
}
    
    
function categoryName($cat){
global $conn;
    $sql = ("SELECT Cat_name FROM Categories WHERE cat_id = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
    $cats = $stm->fetch();
        
    return $cats[0];
}
    
function delete_category($cat){
global $conn;
    $sql = ("DELETE FROM categories WHERE cat_id = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($cat));
}
    
    
    
    
function recent_inquiries(){
global $conn;
    
    $sql = ("SELECT * FROM inquiries ORDER BY date DESC LIMIT 10");
    $stm = $conn->prepare($sql);
    $stm->execute(array());
    $listings = $stm->fetchAll();
    
    $loop = "<ul class='well'>";
    
    foreach ($listings as $row){
        $loop.= "<h3 class='text-center'>".$row['question']."</h3>";
        $loop.= "<p>".$row['message']."</p>";
        $loop.= "Name: <b>".$row['name']."</b>";
        $loop.= "<span class='pull-right'>Email: <b>".$row['email']."</b><br></span>";
        $loop.= "<div class='clearfix'></div>";
        if(isset($row['website'])){            $loop.="Website: <b>".$row['website']."</b>";        }
        $loop.= "<span class='pull-right'>Date: <b>".$row['date']."</b></span>";
        $loop.= "<form action='send-inquiry.php' method='POST'><fieldset>";
        
        $loop.= "<input type='hidden' name='name' value='".$row['name']."' />";
        $loop.= "<input type='hidden' name='email' value='".$row['email']."' />";
        $loop.= "<input type='hidden' name='question' value='".$row['question']."' />";
        $loop.= "<input type='hidden' name='message' value='".$row['message']."' />";
        $loop.= "<input type='hidden' name='ID' value='".$row['ID']."' />";
        
        
        
        $loop.= "<div class='form-group'><label for='response'>Your Response</label>";
        $loop.= "<textarea name='response' class='form-control' rows='5' required ></textarea>    </div>";
        
        $loop.= "<button type='submit' name='submit' class='btn btn-default btn-block'>Reply</button>";
        $loop.= "</fieldset></form>";
    }
    $loop .= "</ul>";
    echo $loop;
}

function respond_inquiry($to, $header, $message){
    mail($to, $header, $message);
}
    
function deleteInquiry($id){
global $conn;
    $sql = ("DELETE FROM inquiries WHERE ID = ?");
    $stm = $conn->prepare($sql);
    $stm->execute(array($id));
    
    header( 'Location: inquiries.php' );
}
    
    
function admin(){
    if((isset($_SESSION['admin'])) && ($_SESSION['admin'] == ADMIN_SECRET))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>