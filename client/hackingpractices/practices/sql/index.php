<?php
    include "../../../../server/lib.php";
    SystemOutput::$debug = false;
?>
<html>
    <body>
        <?php
        if(!isset($_POST["submit"])){
        ?>
        <font color="blue">Please login into your account</font>
        <form action="#" method="post">
            Username: <input type="text" name="username"><br>
            Password: <input type="text" name="password"><br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php 
        }else{
            $username = $_POST["username"];
            $password = $_POST["password"];
            $userResult = DatabaseControls::getRecordsWithFilter("users", "username='$username' and password='$password'");
            if($userResult){
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
        
        <div class="attackinfo" style="margin-top: 10em;">
            <h2>What is SQL Injection?</h2>
            <pre>
            SQL injection is a code injection technique that might destroy your database.

            For real examples, please visit the reference link down below.

            If you want to complete this practice, you may also look at the answer down
            below assuming you have the concept behind SQL Injection.
            
            Reference:
            <a href="https://www.w3schools.com/sql/sql_injection.asp">https://www.w3schools.com/sql/sql_injection.asp</a>
            </pre>
        </div>
        <div class="answerlabel" style="margin-top: 2em;">
            <u>Hover here for answer</u>
            <pre class="answer">
                Username: whatever
                Password: ' or 1=1#
            </pre>
        </div>
    </body>
    <style>
        .answer{
            transition: opacity 1s;
            opacity: 0;
        }
        .answerlabel:hover .answer{
            opacity: 1;
        }
    </style>
</html>