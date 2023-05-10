<html>
    <body>
        <!-- Directory Traversal -->
        <?php
            include "../../../../server/lib.php";
            SystemOutput::$debug = true;
            SystemOutput::printlndbg("You know there is a confidential document in ./confidential/ directory. Find it.");
        ?>
        <div class="answerlabel" style="margin-top: 10em;">
            <u>Hover here for answer</u>
            <pre class="answer">
                goto http://127.0.0.1/vulnapp/client/hackingpractices/practices/getfile/condidential/
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