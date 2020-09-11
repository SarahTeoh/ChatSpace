<?php

$data = array("user" => $_POST["user"], "message" => $_POST["message"]);
echo 'console.log($data["user"])';
$string = file_get_contents('./data.json');

$json = json_decode($string);
array_push($json->freeTalk, $data);
$returnData = json_encode($data);
$jsonData = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
file_put_contents("./data.json", $jsonData);
return $returnData;
exit;
?>
