<?
session_start();
require_once __DIR__.'/lib/config.php';
require_once (__DIR__.'/lib/connect.php');

$id_invoice		= $_GET['id_invoice'];
$id_site		= $_GET['id_site'];
$tx_transaction_hash    = $_GET['transaction_hash'];
$value_in_btc		= $_GET['value'] / 100000000;

if ($_GET['destination_address'] != BITCOIN_ADDRESS) 
{
    echo 'Incorrect Receiving Address';
    return;
}

if ($_GET['secret'] != SECRET) 
{
    echo 'Invalid Secret';
    return;
}

if(($_GET['confirmations'] >= CONFIRMATIONS))
{
    $sql_update_invoice = sprintf("
    REPLACE INTO invoice_payments (id_invoice, tx_transaction_hash, val_price_btc, id_site)
    VALUES (:invoice_id, :tx_transaction_hash, :val_price_btc, :id_site)");
    
    $stm = $conn->prepare($sql_update_invoice);
    
    $success = $stm->execute(array(
	':id_invoice'			=> $id_invoice,
	':tx_transaction_hash'		=> $tx_transaction_hash,
	':val_price_btc'		=> $value_in_btc,
	':id_site'			=> $id_site));
    
   echo $success ? '*ok*' : 'failed';
}
else
{    
    $sql_update_pending_invoice = sprintf("
    REPLACE INTO pending_invoice_payments (id_invoice, tx_transaction_hash, val_price_btc, id_site)
    VALUES (:id_invoice, :tx_transaction_hash, :val_price_btc, id_site)");
    
    $stm = $conn->prepare($sql_update_pending_invoice);
    
    $success = $stm->execute(array(
	':id_invoice'			=> $id_invoice,
	':tx_transaction_hash'		=> $tx_transaction_hash,
	':val_price_btc'		=> $value_in_btc,
	':id_site'			=> $id_site));
    
    echo $success ? 'Waiting for confirmations' : 'There was an error processing this request';
}