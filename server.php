<?php

// Set up host and port
$host = "127.0.0.1";
$port = 9705;
header('Access-Control-Allow-Origin: *');

// Prevent time out
set_time_limit(0);

// Create socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

// Bind socket to port
$result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");

// Listen to the socket until termination
while(1)
{
    // Set maximum backlog of socket. In this case, only a maximum of 3 processes can be in queue
    socket_listen($socket, 3) or die("Could not set up socket listener\n");

    // Accept client connecting to socket
    $client = socket_accept($socket);

    // Read 1024 bytes input from socket
    $input = socket_read($client, 1024);

    // Catch incoming request into array
    $incoming = array();
    $incoming = explode("\r\n", $input);

    // Get name of file requested and method
    $fetchArray = array();
    $fetchArray = explode(" ", $incoming[0]);

    // Name of file requested
    $file = $fetchArray[1];

    // Show homepage
    if($file == "/"){

        $file = "show_topic.html";

    } else {
        $filearray = array();
        $filearray = explode("/", $file, 2);
        $file = $filearray[1];
    }

// Print out request method and file
echo $fetchArray[0] . " Request " . $file . "\n";

// Variable to store output(Header + Content)
$output = "";

// Handle Ajax call
$isAjax = explode("/", $filearray[1]);
if($isAjax[0]=="ajax"){
    $Header = "HTTP/1.1 200 OK \r\n" .
    "Date: Fri, 1 Jan 2020 23:59:59 GMT \r\n" .
    "Content-Type: application/json ; charset=UTF-8 \r\n\r\n";
  if($isAjax[1]=="data.json"){ // Get data from json
    $Content = file_get_contents($file);
    }
    else{ // If POSTed from form
      if($fetchArray[0]=='POST'){
          $parameters = explode("&", $incoming[15]);
          $user = urldecode(explode("=", $parameters[0])[1]);
          $message = urldecode(explode("=", $parameters[1])[1]);
        if(empty($user)){ // Checks whether both parameters are set
          $user = "No name";
        }
        if(empty($message)){
          $message = "No message";
        }
        
        $Content = include($isAjax[0] . "/" . $isAjax[1]); // If parameters are set, send to returnJson.php to process and catch the return
      }
    }
  }else if (explode(".", $filearray[1])[1]=="css") { // Handle css request
    $Header = "HTTP/1.1 200 OK \r\n" .
    "Date: Fri, 1 Jan 2020 23:59:59 GMT \r\n" .
    "Content-Type: text/css; charset=UTF-8 \r\n\r\n";

    $Content = file_get_contents($file);
  }else{
      $Header = "HTTP/1.1 200 OK \r\n" .
      "Date: Fri, 1 Jan 2020 23:59:59 GMT \r\n" .
      "Content-Type: text/html; charset=UTF-8 \r\n\r\n";

      $Content = file_get_contents($file);
    };

$output = $Header . $Content;


// Write output to socket
socket_write($client,$output,strlen($output));

// Close socket after done
socket_close($client);
}

?>
