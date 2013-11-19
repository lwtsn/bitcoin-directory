<?
require_once (__DIR__.'/lib/common.php');

if ($_GET['address'] != BITCOIN_ADDRESS ) {
    echo 'Incorrect Receiving Address';
  return;
}

if ($_GET['secret'] != ADMIN_SECRET ) {
  echo 'Invalid Secret';
  return;
}


if($_GET['listing_id'])
{
    $listing_id = $_GET['listing_id'];    
    $invoice_id = $_GET['invoice_id'];
    $transaction_hash = $_GET['transaction_hash'];
    $value_in_btc = $_GET['value'] / 100000000;

    echo 'ok';
}

