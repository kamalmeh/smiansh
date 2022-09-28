<?php
include "authorization.php";
session_start();
$sql = "UPDATE UserTb SET SessionActive='D'
                WHERE SessionId='" . $_SESSION['id'] . "'";
if ($db->query($sql) === false) {
    echo "There seems to be some issue, pease try after sometime.";
}
session_destroy();
unset($_SESSION['user']);
unset($_SESSION['id']);
unset($_SESSION['LAST_ACTIVITY']);
header("Refresh: 0; URL=index.php");