<?
session_start();
require_once (__DIR__.'/lib/common.php');

$id_invoice = $_GET['id_invoice'];
$amount_pending_btc = 0;
$amount_received_btc = 0;

function payment_recieved($id_site){
global $conn;
    
    $sql_listing_details = sprintf("
	SELECT *
	FROM pending_listing
	WHERE id_site = ?
	LIMIT 1");
    $id_site;
    $stm = $conn->prepare($sql_listing_details);
    $stm->execute(array($id_site));
    $pending_listings = $stm->fetchAll();
    
    if($pending_listings)
    {
    foreach($pending_listings as $listing_row)
    {
	$sql_new_listing = sprintf("
	    REPLACE INTO listing
	    SET id_site			= :id_site,
		nm_site_name		= :nm_site_name,
		url_site_address	= :url_site_address,
		tx_site_description	= :tx_site_description,
		tx_site_image		= :tx_site_image,
		int_category		= :int_category,
		bool_premium		= :bool_premium,
		bool_featured		= :bool_featured,
		dt_date_listed		= :dt_date_listed");

	$stm = $conn->prepare($sql_new_listing);
	
	 $stm->execute(array(
	     ':id_site'		    =>	    $listing_row['id_site'],
	     ':nm_site_name'	    =>	    $listing_row['nm_site_name'],
	     ':url_site_address'    =>	    $listing_row['url_site_address'],
	     ':tx_site_description' =>	    $listing_row['tx_site_description'],
	     ':tx_site_image'	    =>	    $listing_row['tx_site_image'],
	     ':int_category'	    =>	    $listing_row['int_category'],
	     ':bool_premium'	    =>	    $listing_row['bool_premium'],
	     ':bool_featured'	    =>	    $listing_row['bool_featured'],
	     ':dt_date_listed'	    =>	    date('Y-m-d'),
	     ':bool_approved'	    =>	    1));
    }
    
    $sql_delete_pending = sprintf("DELETE FROM pending_listing WHERE id_site = :id_site");
    $stm = $conn->prepare($sql_delete_pending);
    $stm->execute(array(':id_site'  =>	$id_site));
    }
    else
    {
	return;
    }
}


    //Select price for invoice
$sql_select_invoice_listing = sprintf("
    SELECT  val_price_btc, id_site
    FROM    invoice 
    WHERE   id_invoice = ?");

    $stm = $conn->prepare($sql_select_invoice_listing);
    $stm->execute(array($id_invoice));
    $invoice_listing = $stm->fetchAll();

    foreach($invoice_listing as $invoice_listings){
	$total_payment_required = number_format($invoice_listings['val_price_btc'], 8);
	$id_site = $invoice_listings['id_site'];
    }
    
    //Select confirmed payments
$sql_invoice_payment = sprintf("
    SELECT  val_price_btc
    FROM    invoice_payments 
    WHERE   id_invoice = ?");

    $stm = $conn->prepare($sql_invoice_payment);
    $stm->execute(array($id_invoice));
    $invoice_payment = $stm->fetchAll();

    foreach($invoice_payment as $received_payment_row){
	$amount_received_btc += number_format($received_payment_row['val_price_btc'], 8);
    }
    
    //Select unconfirmed payments
$sql_pending_invoice_payment = sprintf("
    SELECT  val_price_btc
    FROM    pending_invoice_payments
    WHERE   id_invoice = ?");

    $stm = $conn->prepare($sql_pending_invoice_payment);
    $stm->execute(array($id_invoice));
    $pending_invoice_payment = $stm->fetchAll();

    foreach($pending_invoice_payment as $pending_payment_row){
	$amount_pending_btc += number_format($pending_payment_row['val_price_btc'], 8);
    }

if($amount_received_btc >= $total_payment_required)
{    
payment_recieved($id_site);
?>    
    <html>
	<head>
	    <title>Payment confirmed for order number <?= $id_invoice ?></title>
	</head>
	<body>
	    <h1>
		Payment confirmed for order number : <?= $id_invoice ?> 
	    </h1>
	    <h3>
		Amount due : <?= $total_payment_required ?>
	    </h3>
	    <h3>
		Payment received : <?= $amount_received_btc ?>
	    </h3>
	    <h3>
		<p>Thank you for placing your order in <?= SITE_NAME ?>, your listing is being approved by a moderator.</p>
		<p>You will be notified via email when it has been confirmed</p>
	    </h3>
	    <a href='<?= SITE_ROOT ?>'>Return to site</a>
	</body>
    </html>
<?
}
else
{
?>
    <html>
	<head>
	    <title>Waiting for payment for order number <?= $id_invoice ?></title>
	</head>
	<body>
	    <h1>
		Payment unconfirmed for order number : <?= $id_invoice ?>
	    </h1>
	    <h3>
		Amount due : <?= $total_payment_required ?>
	    </h3>
	    <h3>
		Payment received : <?= $amount_received_btc ?>
	    </h3>
	    <h3>
		Unconfirmed payments : <?= $amount_pending_btc ?>
	    </h3>
	    <h3>
		<a href='<?=SITE_ROOT?>order_status.php?id_invoice=<?= $id_invoice ?>'>Refresh</a>
	    </h3>
	</body>
    </html>
<?
}