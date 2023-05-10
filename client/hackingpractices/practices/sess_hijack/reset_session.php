<?php
    include "../../../../server/lib.php";

    Cookie::reset("PHPSESSID", "/");
    header("location: login.php");
?>