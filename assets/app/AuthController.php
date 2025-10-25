<?php

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';


if ($username == 'diego') {
    
    if ($password == 'vildosola') {

        
        header("Location: ../../home.html");
        exit();
    } 
}else{ 
    header("Location: ../../index.html");
    exit();
}
    
?>