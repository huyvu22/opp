<?php

require_once 'config.php';
require_once 'includes/Database.php';

$db = new Database();
//$sql = "SELECT * FROM users";
//
//$statement = $db->query($sql, [], true);
//var_dump($statement);
//
//$data = $statement->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>';
//print_r($data);
//echo '</pre>';

//$sql = "SELECT * FROM users ORDER BY name";
//$data = $db->get($sql);
//echo '<pre>';
//print_r($data);
//echo '</pre>';

$sql = "SELECT * FROM users";
$data = $db->getFirst($sql);
echo '<pre>';
print_r($data);
echo '</pre>';

