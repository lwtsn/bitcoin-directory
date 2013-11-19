<?php
try
{
$conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;",$DB_USERNAME, $DB_PASSWORD);
$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e)
{
    echo 'ERROR: ' . $e -> getMessage();
}