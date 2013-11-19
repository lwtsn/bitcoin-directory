<?php
require ('lib/common.php');
    
    if(!admin()){
        header ("location: login.php");
    }
    
if(!admin()){
    echo "hey buddy";
}

if(isset($_GET['removeid'])){
    deleteInquiry($_GET['removeid']);
}
    
    require ('inc/header.php');
	require ('inc/menu.php');

?>

<div class='col-12'>
<?php
    echo "<h3>Message sent to : ". $_POST['email']. "</h3>";
    echo "<h4>In response to : ". $_POST['question']. "</h4>";
    echo "<p>".$_POST['message']. "</p>";
    echo "<h4>Your response : </h4><p>".$_POST['response']. "</p>";

respond_inquiry($_POST['email'], "RE:".$_POST['question'], $_POST['response']);
    
echo "<span class='alert alert-info col-12 text-center'>Message Sent</span>";
    
?>

<a class='btn btn-warning view-listing col-6' href='inquiries.php' style='text-align:center;'>Back</a>
<a onclick='return makesure();' class='btn btn-danger view-listing col-6' href='?removeid=<?php echo $_POST['ID'] ?>' style='text-align:center;'>Delete Message</a>



</div>