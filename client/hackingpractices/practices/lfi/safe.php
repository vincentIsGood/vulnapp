<html>
    <body>
        <?php
            include "../../../../server/lib.php";
            SystemOutput::$debug = true;

            // https://security.stackexchange.com/questions/48187/null-byte-injection-on-php
            // null byte is fixed in version >= PHP 5.3.4
            if(isset($_GET["lang"])){
                $file = $_GET["lang"];

                if($file == "en.php" || $file == "jp.php"){
                    include $file;
                }else{
                    SystemOutput::printlnerr("File not found.");
                }
                
            }else{
                header("Location: ./index.php?lang=en.php");
            }
        ?>
        <br>
        You know the confidential file is around here (Go to the grand parent (up 2 levels) directory) in plain text<br>
        <br>
        Language:<br>
        <a href="./index.php?lang=en.php">English</a><br>
        <a href="./index.php?lang=jp.php">Japanese</a>
    </body>
</html>