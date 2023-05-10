<?php
    include "../../../../server/lib.php";
    SystemOutput::$debug = false;
?>
<html>
    <body>
        <?php
            if(Session::loadSessionData()){
                header("location: logged.php");
            }
            if(!isset($_POST["submit"])){
                SystemOutput::println("admin has logged in.");
                SystemOutput::println("<font color='blue'>Find his session id.</font>");
        ?>
        <form action="#" method="post">
            Username: <input type="text" name="username"><br>
            Password: <input type="text" name="password"><br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php
            }else{
                // Create a new session upon login.
                Session::createSession(random_int(0, 999));
                $_SESSION["username"] = $_POST["username"];
                header("location: logged.php");
            }
        ?>


        <div class="attackinfo" style="margin-top: 10em;">
            <h2>What is Session Hijacking?</h2>
            <pre>
            Session hijacking is an attack where a user session is taken over by an attacker. 
            A session starts when you log into a service, for example your banking application, 
            and ends when you log out. The attack relies on the attacker’s knowledge of your session cookie, 
            so it is also called cookie hijacking or cookie side-jacking

            This time, we are bruteforcing a weak session ID in order to gain an admin session.

            Reference:
            <a href="https://www.netsparker.com/blog/web-security/session-hijacking/">https://www.netsparker.com/blog/web-security/session-hijacking/</a>
            </pre>
        </div>
        <div class="answerlabel" style="margin-top: 2em;">
            <u>Hover here for answer</u>
            <pre class="answer">
                Use python to run the script at /vulnapp/client/scripts/sess_hijack.py
                (ie. At your command line, change directory to path/to/xampp/htdocs/vulnapp/ 
                and type "python client/scripts/sess_hijack.py")
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