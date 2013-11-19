<?
session_start();
require_once (__DIR__.'/lib/common.php');
require_once (__DIR__.'/securimage/securimage.php');

require(__DIR__.'/inc/blocks/header.php');
require(__DIR__.'/inc/blocks/menu.php');

$amount = 0;
$id_site = $_GET['id_site'];
$success = false;
if(isset($_POST['featured']))
{
    $featured = 'have';
    $featuredAlert = 'success';
    $bool_featured = 1;
    $amount += FEATURED_COST;
}
else
{    
    $featured = 'have not';
    $featuredAlert = 'danger'; 
    $bool_featured = 0;
}
if(isset($_POST['premium']))
{
    $premium = 'have';
    $premiumAlert = 'success';
    $bool_premium = 1;
    $amount += PREMIUM_COST;
}
else
{    
    $premium = 'have not';
    $premiumAlert = 'danger'; 
    $bool_premium = 0;
}

if($amount > 0)
{
    $price = 'Total cost ' . $amount;
}
 else 
{
    $price = 'STANDARD LISTING';
}

$sql_new_listing = sprintf("
	UPDATE pending_listing SET 
	bool_premium	= :bool_premium, 
	bool_featured	= :bool_featured
	WHERE id_site	= :id_site ");
$stm = $conn->prepare($sql_new_listing);

$stm->execute(array(
    ':bool_premium'	=> $bool_premium,
    ':bool_featured'	=> $bool_featured,
    ':id_site'		=> $id_site
	));

$sql_check_invoice = ("SELECT id_site, id_invoice FROM invoice WHERE id_site = ? LIMIT 1");

$stm = $conn->prepare($sql_check_invoice);
$stm->execute(array($id_site));
$existing_invoice = $stm->fetchAll();

//Check if an invoice already exists for the site
if($existing_invoice)
{
    foreach($existing_invoice as $row)
    {
	$id_invoice = $row['id_invoice'];
    }
    $sql_update_invoice = sprintf("
    UPDATE invoice SET val_price_btc = :val_price_btc WHERE id_invoice = :id_invoice");
    
    $stm = $conn->prepare($sql_update_invoice);
    
    $stm->execute(array(
	':val_price_btc' => $amount,
	':id_invoice'	 => $id_invoice
    ));
}
else
{
    $blockchain = 'https://blockchain.info/api/receive'; $callback = urlencode(SITE_ROOT . 'callback-payment.php');
    $parameters = "method=create&address=".BITCOIN_ADDRESS."&callback={$callback}&id_site={$id_site}&secret=".ADMIN_SECRET;
    $response = file_get_contents($blockchain . '?' . $parameters);
    $bitcoinPayment = json_decode($response);
    $payment_address = $bitcoinPayment->input_address;
    
    $sql_new_invoice = sprintf("
    REPLACE INTO invoice (tx_input_address, tx_destination_address, id_site, val_price_btc)
    VALUES (:tx_input_address, :tx_destination_address, :id_site, :val_price_btc)");

    $stm = $conn->prepare($sql_new_invoice);

    $stm->execute(array(
	':tx_input_address'		=> $payment_address,
	':tx_destination_address'	=> BITCOIN_ADDRESS,
	':id_site'			=> $id_site,
	':val_price_btc'		=> $amount));

    $id_invoice = $conn->lastInsertId('id_invoice');
}

echo "
<div class='alert alert-{$featuredAlert}'>
    <h2>
        <icon class='icon-star'></icon>   You have {$featured} selected a featured listing
    </h2>
</div>";
echo "
<div class='alert alert-{$premiumAlert}'>
    <h2>
    <icon class='icon-trophy'></icon>   You have {$premium} selected a featured listing
    </h2>
</div>";

?>
 
<div style='margin-top:50px;' class="panel-group" id="accordion">

    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
          Checkout
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
    <div class="panel-body">
<?
	if($success)
	{
	    echo "<table>
	    <tr class='active'><td>Payment details</td></tr> 
	    <tr class='success'><td>{$payment_address}</td></tr>
	    <tr class='warning'><td>{$amount}</td></tr>
	    </table>";
	}
?>
        <h1>
	</h1>
    </div>
    </div>
Waiting for Payment Confirmation: <?="<a href='./order_status.php?id_invoice={$id_invoice}'>Refresh</a>" ?>

  </div>
</div>
    
    
    
</div><!--end content-->

</body>