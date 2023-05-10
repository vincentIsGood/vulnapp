<html>
    <body>
        <?php
            include "../../../../server/lib.php";
            SystemOutput::$debug = true;

            // https://security.stackexchange.com/questions/48187/null-byte-injection-on-php
            // null byte is fixed in version >= PHP 5.3.4
            if(isset($_GET["lang"])){
                $file = $_GET["lang"];

                include $file;
                
            }else{
                header("Location: ./index.php?lang=en.php");
            }
        ?>
        <br>
        You know the confidential file, called "confidential/asd.txt", is around here (Go to the grand parent (up 2 levels) directory) in plain text. Find it<br>
        <br>
        Language:<br>
        <a href="./index.php?lang=en.php">English</a><br>
        <a href="./index.php?lang=jp.php">Japanese</a>
        
        <div class="attackinfo" style="margin-top: 10em;">
            <h2>What is Local File Inclusion?</h2>
            <pre>
            Basically this is similar to directory traversal.
            
            Usage Example
            You have a collection of .txt files with help texts and want to make them available through a web application. These files are reachable through a link such as:
            
            https://example.com/?helpfile=login.txt
            In this scenario the content of the text file will be printed directly to the page without using a database to store the information.
            
            Reference:
            <a href="https://www.netsparker.com/blog/web-security/local-file-inclusion-vulnerability/">https://www.netsparker.com/blog/web-security/local-file-inclusion-vulnerability/</a>
            </pre>
        </div>
        <div class="answerlabel" style="margin-top: 2em;">
            <u>Hover here for answer</u>
            <pre class="answer">
                goto http://127.0.0.1/vulnapp/client/hackingpractices/practices/lfi/index.php?lang=../../confidential/asd.txt
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