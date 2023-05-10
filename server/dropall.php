<?php
    include "./lib.php";
    SystemOutput::$debug = true;
    
    DatabaseControls::query("drop table users;");
    DatabaseControls::query("drop table comments;");
?>