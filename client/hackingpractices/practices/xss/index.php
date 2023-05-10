<?php
    include "../../../../server/lib.php";
    SystemOutput::$debug = false;
?>
<html>
    <body>
        <font color="blue">Get your own cookie by posting a comment</font><br>
        Post your comment
        <form action="#" method="post">
            Username: <input type="text" name="username"><br>
            Comment: <textarea type="text" name="comment"></textarea><br>
            <input type="submit" name="submit" value="Submit">
            <input type="submit" name="submit" value="Reset Comments">
        </form>
        <?php
        Cookie::set("xss", "true", null, "/");
        if(isset($_POST["submit"])){
            if($_POST["submit"] != "Reset Comments"){
                $username = $_POST["username"];
                $comment = $_POST["comment"];
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
        
        <div class="attackinfo" style="margin-top: 10em;">
            <h2>What is Cross Site Scripting?</h2>
            <pre>
            Cross-site scripting is a type of security vulnerability typically found in web applications. 
            XSS attacks enable attackers to inject client-side scripts into web pages viewed by other users
            
            Client-side scripts include JavaScript and HTML.

            Reference:
            <a href="https://en.wikipedia.org/wiki/Cross-site_scripting">https://en.wikipedia.org/wiki/Cross-site_scripting</a>
            </pre>
        </div>
        <div class="answerlabel" style="margin-top: 2em;">
            <u>Hover here for answer</u>
            <pre class="answer">
                Username: whatever
                Comment: &lt;script&gt;alert(document.cookie)&lt;/script&gt;
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