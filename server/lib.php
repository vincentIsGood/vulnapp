<?php
    /**
     * The whole library "lib.php" was created by Vincent Ko on 4/6/2020 (DD/MM/YYYY).
     * It is still maintained by Vincent Ko till this day (as of 21/12/2020).
     * 
     * This php library is used to deal with some common problems encountered while 
     * creating a web application. Specifically, database connection can be simplified,
     * session and cookies are simplified. File uploading made easier and more. Since
     * most of the functions and methods you encounter here are static, it is safe to
     * say that the feature of functional programming is still preserved. 
     */
    if(!isset($declaredLib0123)){
    $declaredLib0123 = true;
    
    function strStartsWith($string, $startString){ 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    }

    /**
     * eg. fillObject($_GET, $recordAfterFetch); This function is especially useful when you are using an UPDATE request
     * @param mixed $newVals
     * @param mixed $oldObj
     */
    function fillObject($newVals, $oldObj){
        foreach($oldObj as $k => $v){
            if(isset($newVals[$k])){
                $oldObj[$k] = $newVals[$k];
            }
        }
        return $oldObj;
    }

    function genKeys($length){
        $res = "";
        for($i = 0; $i < $length; $i++){
            if(rand(0, 1) < 0.5){
                $res .= chr(random_int(65, 90));
            }else{
                $res .= chr(random_int(97, 122));
            }
        }
        return $res;
    }

    class SystemOutput{
        public static $debug = false;

        public static function print($msg){
            if(gettype($msg) === "array" || gettype($msg) === "object"){
                print_r($msg);
            }else
                print $msg;
        }

        public static function println($msg){
            if(gettype($msg) === "array" || gettype($msg) === "object"){
                print_r($msg);
                print "<br>";
            }else
                print "$msg<br>";
        }
    
        public static function printlndbg($msg){
            if(SystemOutput::$debug){
                if(gettype($msg) === "array" || gettype($msg) === "object"){
                    print "<font style='color: blue;'>";
                    print_r($msg);
                    print "<br></font>";
                }else
                    print "<font style='color: blue;'>$msg<br></font>";
            }
        }
    
        public static function printlnerr($msg){
            if(SystemOutput::$debug){
                if(gettype($msg) === "array" || gettype($msg) === "object"){
                    print "<font style='color: red;'>";
                    print_r($msg);
                    print "<br></font>";
                }else
                    print "<font style='color: red;'>$msg<br></font>";
            }
        }
    }

    // W3C is used as reference
    class DatabaseConnection{
        private static $conn = null;
        private static $databaseName = "vulndatabase";
        private static $username = "root";
        private static $password = "";

        public static function init(){
            if(DatabaseConnection::$conn){
                SystemOutput::printlndbg("Connection has been initialized before");
            }else{
                SystemOutput::printlndbg("Initializing database connection");
                try{
                    DatabaseConnection::$conn = new PDO("mysql:host=127.0.0.1;dbname=" . DatabaseConnection::$databaseName, DatabaseConnection::$username, DatabaseConnection::$password);
                    DatabaseConnection::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    SystemOutput::printlndbg("Connected successfully");
                }catch(PDOException $e){
                    SystemOutput::printlnerr("Error Occurred: " . $e->getMessage() . ". Closing");
                    DatabaseConnection::close();
                }
            }
        }

        public static function close(){
            DatabaseConnection::$conn = null;
        }
        
        /**
         * @return PDO
         */
        public static function getPDO(){
            if(DatabaseConnection::$conn){
                return DatabaseConnection::$conn;
            }else{
                SystemOutput::printlndbg("Connection is terminated");
                return null;
            }
        }
    }
    class DatabaseControls{
        static function createTable($tableName, $tableParamsInSql){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            try{
                $statement = $pdo->query("select * from $tableName;");
                if($statement){
                    SystemOutput::printlndbg("Table exists. No need to create a new one");
                }
            }catch(PDOException $e){
                SystemOutput::printlnerr("Cannot find table: $e");
                SystemOutput::printlnerr("Creating a new one...");
                $pdo->query("create table $tableName($tableParamsInSql);");
            }
        }

        /**
         * The original query function called from a PDO object (useful for union selections and more...)
         * @param mixed $queryStr query string (eg. "select * from users;")
         * @return PDOStatement null if the query cannot be processed / failed to execute
         */
        static function query($queryStr){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            try{
                $statement = $pdo->query("$queryStr");
                return $statement;
            }catch(PDOException $e){
                SystemOutput::printlnerr("Cannot process query");
                SystemOutput::printlnerr("Error explanation as follows: $e");
            }
            return null;
        }

        /**
         * For dates, please follow this format 'YYYY-MM-DD'
         * @param mixed $tableName table name
         * @param mixed $values all values needed to create a new record for that table (eg. [$id, $name, $password, $email, $phone, $address])
         */
        static function createRecord($tableName, $values){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            try{
                $concated = "";
                foreach($values as $value){
                    if(strtolower(gettype($value)) === "integer" || strtolower(gettype($value)) === "double"){
                        $concated .= $value;
                    }else{
                        // specify Strings for any queries using ' or "
                        $concated .= "'$value'";
                    }
                    $concated .= ",";
                }
                $concated = substr($concated, 0, strlen($concated)-1);
                
                $statement = $pdo->query("insert into $tableName values($concated);");
                if($statement === false){
                    SystemOutput::printlndbg("Something went wrong. Record cannot be added.");
                }else{
                    SystemOutput::printlndbg("New record has been successfully added");
                }
            }catch(PDOException $e){
                SystemOutput::printlnerr($e->getMessage());
            }
        }

        /**
         * @param mixed $tableName table name
         * @param mixed $conditions [nullable] conditions needed for filtering (eg. id="..." // id=123)
         * @return PDOStatement|false|null [nullable] result(s) retrieved from the database (null if any error occurred)
         */
        static function getRecordsWithFilter($tableName, $conditions){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            try{
                if($conditions){
                    $statement = $pdo->query("select * from $tableName where $conditions;");
                }else{
                    $statement = $pdo->query("select * from $tableName;");
                }
                return $statement;
            }catch(PDOException $e){
                SystemOutput::printlnerr("Cannot fetch records from table: $tableName");
                SystemOutput::printlnerr($e->getMessage());
            }
            return null;
        }
        
        /**
         * @return PDOStatement
         */
        static function getRecordsWithoutFilter($tableName){
            return DatabaseControls::getRecordsWithFilter($tableName, null);
        }

        /**
         * @param mixed $tableName table name
         * @param mixed $conditions conditions for updating specific portions of people
         * @param mixed $colValWithType similar to the "map" of createRecord(...). eg. ["id"=>1234, "name"=>"somename", ...]
         */
        static function updateRecordWithFilter($tableName, $conditions, $colValWithType){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            try{
                $concated = "";
                foreach($colValWithType as $column => $value){
                    $concated .= $column . "=";
                    if(strtolower(gettype($value)) === "integer" || strtolower(gettype($value)) === "double"){
                        $concated .= $value;
                    }else{
                        // specify Strings for any queries using ' or "
                        $concated .= "'$value'";
                    }
                    $concated .= ",";
                }
                $concated = substr($concated, 0, strlen($concated)-1);
                
                $statement = null;
                if($conditions !== null){
                    $statement = $pdo->query("update $tableName set $concated where $conditions");
                }else{
                    $statement = $pdo->query("update $tableName set $concated;");
                }
                if($statement === false){
                    SystemOutput::printlndbg("Something went wrong. Record cannot be updated.");
                }else{
                    SystemOutput::printlndbg("Information of the specified record has been updated");
                }
            }catch(PDOException $e){
                SystemOutput::printlnerr($e->getMessage());
            }
        }

        /**
         * @param String $tableName table name
         * @param mixed $colValWithType similar to the "map" of createRecord(...). eg. ["id"=>1234, "name"=>"somename", ...]
         */
        static function updateRecordWithoutFilter($tableName, $colValWithType){
            DatabaseControls::updateRecordWithFilter($tableName, null, $colValWithType);
        }

        /**
         * @param mixed $conditions simple delete conditions (eg. id="..." // id=123)
         */
        static function deleteRecord($tableName, $conditions){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            try{
                $statement = $pdo->query("delete from $tableName where $conditions;");
                if($statement){
                    SystemOutput::printlndbg("Delete operation completed");
                }
            }catch(PDOException $e){
                SystemOutput::printlnerr("Cannot delete the required record(s)");
                SystemOutput::printlnerr($e->getMessage());
            }
        }

        /**
         * Extract from StackOverflow...
         * execute(array(":op"=>"twitter", ":ou"=>$user_info->id));
         * Automatically detects ':op' as String, ':ou' as Int.
         * 
         * @param String $statement SQL statement
         * @param array $array null if no arguments needed. Otherwise, its an array of arguments. 
         *              eg. (array(':id' => '123', ':name' => 'john') OR [':id' => '123', ':name' => 'john'])
         * @return PDOStatement|bool|null PDOStatement/bool if it is success, null if it fails
         */
        static function prepareExecute($statement, $array){
            DatabaseConnection::init();
            $pdo = DatabaseConnection::getPDO();
            $pdostatement = $pdo->prepare($statement);
            if($pdostatement->execute($array)){
                return $pdostatement;
            }else{
                return null;
            }
        }
    }
	
	// Just an alternative (Delete if necessary)
	class Mysql{
        static $databasename = "vulndatabase";
        static $connection = null;
        static $result = null;
        static $currentRecord = 0;

        static function init(){
            if(!Mysql::$connection){
                Mysql::$connection = mysqli_connect("127.0.0.1", "root", "", Mysql::$databasename);
                if(!Mysql::$connection){
                    die("Could not connect: " . mysqli_error(Mysql::$connection));
                }
            }else{
                SystemOutput::printlnerr("Connection has been initialized before");
            }
        }

        static function query($query){
            Mysql::free();
            Mysql::$result = mysqli_query(Mysql::$connection, $query);
            Mysql::checkerr();
            return Mysql::$result;
        }

        static function length(){
            return mysqli_num_rows(Mysql::$result);
        }

        // get records
        static function getRecordByRow(){
            Mysql::$currentRecord++;
            return mysqli_fetch_row(Mysql::$result);
        }

        static function getRecordByAssoc(){
            Mysql::$currentRecord++;
            return mysqli_fetch_assoc(Mysql::$result);
        }

        // same thing
        static function getRecordByNumArray(){
            Mysql::$currentRecord++;
            return mysqli_fetch_array(Mysql::$result, MYSQLI_NUM);
        }

        static function getRecordByAssocArray(){
            Mysql::$currentRecord++;
            return mysqli_fetch_array(Mysql::$result, MYSQLI_ASSOC);
        }

        static function hasNext(){
            if(Mysql::$currentRecord < Mysql::length()){
                return true;
            }
            return false;
        }

        //check for abnormal results
        static function checkerr(){
            if(!Mysql::$result){
                SystemOutput::printlnerr("Error occured in your query: " . mysqli_error(Mysql::$connection));
            }else{
                SystemOutput::printlndbg("Query successfully processed");
            }
        }

        static function close(){
            Mysql::free();
            mysqli_close(Mysql::$connection);
            Mysql::$connection = null;
        }

        static function free(){
            Mysql::$currentRecord = 0;
            if(Mysql::$result){
                // needs an object
                if(Mysql::$result != true && Mysql::$result != false)
                    mysqli_free_result(Mysql::$result);
            }
        }
    }

    class File{
        /**
         * @param Array $fileInfo an array usually passed from $_FILES["name"]
         */
        function __construct($fileInfo){
            $this->fileInfo = $fileInfo;
            $this->tmpFile = $fileInfo["tmp_name"];
        }

        function getName(){
            return $this->fileInfo["name"];
        }

        function getFileExtension(){
            return strtolower(pathinfo($this->fileInfo["name"], PATHINFO_EXTENSION));
        }

        /**
         * @param mixed $extensionTypes all args must be in lower case
         */
        function isTheseType(...$extensionTypes){
            foreach($extensionTypes as $v){
                if($this->getFileExtension() === $v){
                    return true;
                }
            }
            return false;
        }

        function copyTo($path){
            move_uploaded_file($this->tmpFile, $path);
        }

        /**
         * @return int size of a file in Bytes
         */
        function size(){
            return $this->fileInfo["size"];
        }

        static function fileExists($path){
            return file_exists($path);
        }

        /**
         * Does all the dirty work for you in order to handle a file upload (use catch Exception for lazier error handling. Haha)
         * @param File $file a file instance of this class
         * @param String $targetDir directory where the file is going to be stored
         * @param Integer $maxSize max size for a file
         * @throws FileExistsException
         * @throws FileTooLargeException
         * @throws FileExtensionInvalidException
         */
        static function canUploadImage($file, $targetDir, $maxSize){
            $targetFile = $targetDir . random_int(0, 9999) . "_" . $file->getName();
            if($file->isTheseType("png", "jpeg", "jpg", "gif")){
                //1MB
                if($file->size() < $maxSize && $file->size() > 0){
                    if(!File::fileExists($targetFile)){
                        $file->copyTo($targetFile);
                        return $targetFile;
                    }else
                        throw new FileExistsException("File name has been used before");
                }else
                    throw new FileTooLargeException("File is too large / empty");
            }else
                throw new FileExtensionInvalidException("Unsupported file extension detected");
            return null;
        }
    }
    class FileExistsException extends Exception{}
    class FileTooLargeException extends Exception{}
    class FileExtensionInvalidException extends Exception{}

    class Cookie{
        static function get($key){
            return $_COOKIE[$key];
        }

        /**
         * simple set cookie function
         * @param string key string
         * @param string value string
         * @param int use time() + seconds
         * @param string path
         */
        static function set($key, $value = null, $expire = 0, $path = ""){
            if($value != null){
                setcookie($key, $value ?? "null", $expire, $path);
            }else{
                setcookie($key, "null");
            }
        }

        static function reset($key, $path = ""){
            if($path)
                setcookie($key, "null", 10, $path);
            else
                setcookie($key, "null", 10);
        }

        static function exists($key){
            if(isset($_COOKIE[$key])){
                if($_COOKIE[$key] !== "null"){
                    return true;
                }
            }
            return false;
        }
    }

    // TODO: Find ways to make session timouts
    class Session{
        static function exists($key){
            if(isset($_SESSION[$key])){
                if($_SESSION[$key]){
                    return true;
                }
            }
            return false;
        }
        
        /**
         * create a new session if not created.
         * @param string $str create a session with id $str
         */
        static function createSession($str){
            if(!isset($_SESSION)){
                if($str){
                    if(trim($str) !== ""){
                        session_id($str);
                    }
                }
                session_start();
            }
        }
        
        /**
         * load session data if the cookie "PHPSESSID" is set and that the ID is valid.
         * This is a simple trick using session_start() and empty $_SESSION variable
         * to detect if "PHPSESSID" is set.
         * 
         * This is super useful when you want to detect if the use has logged in or not 
         * (if you use session id exclusively after login).
         * @return bool whether "PHPSESSID" exists or not // data loaded or not
         */
        static function loadSessionData(){
            if(Cookie::exists("PHPSESSID")){
                session_start();
                if(empty($_SESSION)){
                    SystemOutput::printlndbg("Invalid Session ID");
                    Session::destroySession();
                    return false;
                }
                return true;
            }
            return false;
        }
    
        /**
         * destroy the whole session along with $_SESSION var
         */
        static function destroySession(){
            if(isset($_SESSION)){
                unset($_SESSION);
                session_destroy();
            }
        }
    }
    
    // A wrapper for array...
    class ArrayList{
        private $length = 0;
        private $originalArray = null;

        function __construct(...$args){
            $this->originalArray = $args;
            $this->length = count($this->originalArray);
        }

        public function add($val){
            array_push($this->originalArray, $val);
            $this->length++;
        }

        public function remove($index){
            array_splice($this->originalArray, $index, 1);
            $this->length--;
        }

        public function get($index){
            return $this->originalArray[$index];
        }

        public function set($index, $val){
            $this->originalArray[$index] = $val;
        }

        public function length(){
            return $this->length;
        }
    }

    }
?>