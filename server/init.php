<?php
    include "./lib.php";
    SystemOutput::$debug = true;

    DatabaseControls::createTable("users", 
        "userId char(10) not null,
        username varchar(30) not null,
        password varchar(30) not null,
        constraint user_pk primary key(userId)"
    );

    DatabaseControls::createRecord("users", [genKeys(10), "john", "starwars"]);
    DatabaseControls::createRecord("users", [genKeys(10), "ken", "lovehacking"]);
    DatabaseControls::createRecord("users", [genKeys(10), "admin", "heaven"]);

    DatabaseControls::createTable("comments",
        "username varchar(30) not null,
        comment varchar(200) not null"
    );
?>