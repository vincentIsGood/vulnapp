<html>
    <body>
        <?php
            // https://httpd.apache.org/docs/2.4/mod/core.html#directory
            include "../../../../server/lib.php";
            SystemOutput::$debug = true;
            SystemOutput::printlndbg("Protection: If you do not want users to access it and you are using apache,");
            SystemOutput::printlndbg("you need to add a few lines inside <font color=red>httpd.conf</font> under <font color=red>apache/conf/</font> directory.");
            SystemOutput::println("");
            SystemOutput::printlndbg("For example, to hide the whole confidential/");
            SystemOutput::printlndbg(
                '<pre>
                    &#60;Directory ~ "/confidential/"&#62;
                        Order Deny,Allow
                        Deny from all
                    &#60;/Directory&#62;
                </pre>'
            );
        ?>
    </body>
</html>