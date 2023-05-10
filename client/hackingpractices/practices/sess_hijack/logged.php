<html>
    <body>
        <?php
            include "../../../../server/lib.php";
            if(Session::loadSessionData()){
                if(Session::exists("userid")){
                    SystemOutput::println("Welcome back, " . $_SESSION["username"]);
                }else{
                    SystemOutput::println("Welcome back, " . $_SESSION["username"]);
                    SystemOutput::println("<font color='red'>You are still not admin</font>");
                }
            }else{
                header("location: login.php");
            }
        ?>
    </body>
</html>