<?php
    
    
    session_start();
    if($_SERVER['REQUEST_METHOD']==="POST"){
        $message="Error has been encountered somewhere in page : ".$_POST['errorPage'];
        mail("contact@smiansh.com", "ERROR on Website", $message, "From: " . $email);
        $_SESSION['ERROR_REPORTED']=1;
        echo "Mail Sent. Redirecting...";
        exit(header("Location:underConstruction.php"));
    }
?>