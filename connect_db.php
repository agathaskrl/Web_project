<?php  
$conn= mysqli_connect('localhost', 'root', '', 'web_project'); //h opws exete onomasei th vash sas ta alla menoun idia  

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?> 