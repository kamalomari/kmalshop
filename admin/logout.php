<?php
session_start();
session_unset();//Unset The Data
session_destroy();//Destroy Session
header("Location:index.php");
exit();
?>