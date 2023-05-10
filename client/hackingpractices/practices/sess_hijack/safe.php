<?php
    include "../../../../server/lib.php";

    function initAdminSession(){
        if(!Session::loadSessionData()){
            $userResult = DatabaseControls::getRecordsWithFilter("users", "username='admin'");
            if($userResult){
                $user = $userResult->fetch();
                if($user){
                    $name = $user['username'];
                    Session::createSession(null);
                    $_SESSION["userid"] = $user['userId'];
                    $_SESSION["username"] = $name;
                    SystemOutput::println("admin is now logged in.");
                    SystemOutput::println("<font color='blue'>Go to login.php and login as admin</font>");
                }else{
                    SystemOutput::printlnerr("Unexpected: Username or Password is incorrect");
                }
            }
        }else{
            SystemOutput::println("admin has logged in.");
            SystemOutput::println("<font color='blue'>Go to login.php and login as admin</font>");
        }
    }

    initAdminSession();
    header("location: reset_session.php");
?>