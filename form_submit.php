<?php

$board = $_GET['whichBoard'];

header("Content-type: application/json; charset=utf-8");
$file = 'ajax/data.json';
$json = file_get_contents($file);
$data = json_decode($json, true);

//delete reading data.json then just change tthe variables to json
echo $data; //jsonオブジェクト化
exit();
 ?>
