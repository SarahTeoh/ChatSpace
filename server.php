<?php

// set up host and port
$host = "127.0.0.1";
$port = 25003;
header('Access-Control-Allow-Origin: *');

// don't timeout!
set_time_limit(0);
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
// bind socket to port
$result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");

// listen to the socket until termination
while(1)
{
    socket_listen($socket, 3) or die("Could not set up socket listener\n");
    $client = socket_accept($socket);

    $input = socket_read($client, 1024);

    $incoming = array();
    $incoming = explode("\r\n", $input);

    $fetchArray = array();
    $fetchArray = explode(" ", $incoming[0]);

    $file = $fetchArray[1];
    if($file == "/"){

        $file = "show_topic.html";

    } else {

        $filearray = array();
        $filearray = explode("/", $file, 2);
        $file = $filearray[1];
    }

echo $fetchArray[0] . " Request " . $file . "\n";
echo $file . "\n";

$output = "";
$isAjax = explode("/", $filearray[1]);
if($isAjax[0]=="ajax"){
  $Header = "HTTP/1.1 200 OK \r\n" .
  "Date: Fri, 1 Jan 2020 23:59:59 GMT \r\n" .
  "Content-Type: application/json \r\n\r\n";
  if($isAjax[1]=="data.json"){
      $Content = file_get_contents($file);
    }
    else{
      $Content = include_once($isAjax[1]);
      echo $Content;
  }
}else{
  $Header = "HTTP/1.1 200 OK \r\n" .
  "Date: Fri, 1 Jan 2020 23:59:59 GMT \r\n" .
  "Content-Type: text/html \r\n\r\n";

  $Content = file_get_contents($file);
};
    $output = $Header . $Content;

    socket_write($client,$output,strlen($output));
    socket_close($client);
}

?>
