<?php
$servername="localhost";
$username='root';
$password="";
$database="judging_system";

$con = mysqli_connect($servername,$username,$password,$database);

if ($con) {
     // echo "Successful connection" . '<br>';
} else
    die('connection failed' . mysqli_connect_errno());
