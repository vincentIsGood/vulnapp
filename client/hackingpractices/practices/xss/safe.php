<?php
    include "../../../../server/lib.php";
    SystemOutput::$debug = false;
?>
<html>
    <body>
        Post your comment
        <form action="#" method="post">
            Username: <input type="text" name="username"><br>
            Comment: <textarea type="text" name="comment"></textarea><br>
            <input type="submit" name="submit" value="Submit">
            <input type="submit" name="submit" value="Reset Comments">
        </form>
        <?php
        Cookie::set("xss", "true", null, "/");
        // htmlspecialchars
        if(isset($_POST["submit"])){
            if($_POST["submit"] != "Reset Comments"){
                $username = htmlspecialchars($_POST["username"]);
                $comment = htmlspecialchars($_POST["comment"]);
                DatabaseControls::createRecord("comments", [$username, $comment]);
            }else{
                DatabaseControls::deleteRecord("comments", "1=1");
            }
        }
        SystemOutput::println("<font style='font-size: 30px;' color=red>Comments:</font>");

        $result = DatabaseControls::getRecordsWithoutFilter("comments");
        $comments = $result->fetchAll();
        if($comments){
            foreach($comments as $comment){
                $user = $comment["username"];
                $com = $comment["comment"];
                SystemOutput::println("<font style='font-size: 26px;' color=blue>User: $user<br>Comment: $com<br></font>");
            }
        }
        ?>
    </body>
</html>