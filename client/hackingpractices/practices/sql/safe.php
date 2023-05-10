<?php
    include "../../../../server/lib.php";
    SystemOutput::$debug = false;
?>
<html>
    <body>
        <?php
        if(!isset($_POST["submit"])){
        ?>
        Please login into your account
        <form action="#" method="post">
            Username: <input type="text" name="username"><br>
            Password: <input type="text" name="password"><br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php 
        }else{
            $username = $_POST["username"];
            $password = $_POST["password"];
            $userResult = DatabaseControls::prepareExecute("select * from users where username=(:username) and password=(:password)", [":username"=>$username, ":password"=>$password]);
            if($userResult){
                SystemOutput::$debug = true;
                $user = $userResult->fetch();
                if($user){
                    $name = $user['username'];
                    SystemOutput::print("<font size=32>Welcome back, $name.</font>");
                }else{
                    SystemOutput::printlnerr("Username or Password is incorrect");
                }
            }
        }
        ?>
    </body>
</html>