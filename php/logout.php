<?php
if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
    session_unset(); 
    header("Location: ./index.php");
}