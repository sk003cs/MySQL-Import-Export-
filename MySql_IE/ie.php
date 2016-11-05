 <?php
    /**
    * @author  SENTHILKUMAR. R(sk003cs@gmail.com)
    * @version 0.0.1
    * @description Export and Import MySQL database
    * @copyright CS-Rockz
   */
    
    define("SHELL_NOT_SUPPORTS", "Sorry!, Your system does not supports shell execution.");
    define("I_SUCCEED", "Imported successfully<br/>");
    define("I_FAILED", "Failed to import<br/>");
    define("E_SUCCEED", "Exported successfully<br/>");
    define("E_FAILED", "Failed to export<br/>");
    
    
    class IE_UTILS {
        private $HOST_NAME, $USER, $PASSWORD, $DATABASE, $FILE_PATH, $COMMAND = "mysql";
        function __construct($HOST_NAME, $USER, $PASSWORD, $DATABASE) {
            $this->HOST_NAME = $HOST_NAME;
            $this->USER = $USER;
            $this->PASSWORD = $PASSWORD;
            $this->DATABASE = $DATABASE;
        }
        
        private function getTempName() {
            return tempnam(sys_get_temp_dir(), 'err');
        }
        
        private function isCommandSupports() {
            $output = array();
            if (preg_match('~win~i', PHP_OS)) {
                exec('where /Q ' . $this->COMMAND, $output, $return_val);
            } else {
                exec('which ' . $this->COMMAND, $output, $return_val);
            }
            return intval($return_val) === 1 ? false : true;
        }
        
        public function export() {
            if(!$this->isCommandSupports())
                throw new SHELL_NOT_SUPPORTS;
            
            try {
                $backup_path = __dir__."/backup/".time()."-".$this->DATABASE.".sql";
                $error_file = $this->getTempName();
                $exportCmd = "mysqldump -h ".$this->HOST_NAME." -u ".$this->USER." --password=".$this->PASSWORD." ".$this->DATABASE." 2> ".$error_file." > ".$backup_path;
                $output = array();
                exec($exportCmd, $output, $return_val);
                if($return_val == 0)
                    return $backup_path;
                else 
                    throw new E_FAILED;
            } catch(Exception $e) {
                throw new $e->getMessage();
            }
        }
        
        public function import($filePath) {
            if(!$this->isCommandSupports())
                throw new SHELL_NOT_SUPPORTS;
            
            try {
                $this->FILE_PATH = $filePath;
                $flag = false;
                $error_file = $this->getTempName();
                $importCmd = "mysql -h ".$this->HOST_NAME." -u ".$this->USER." --password=".$this->PASSWORD." ".$this->DATABASE." < ".$this->FILE_PATH;
                $output = array();
                exec($importCmd, $output, $return_val);
                if($return_val == 0) 
                    $flag = true;
                return $flag;
            } catch(Exception $e) {
                throw new $e->getMessage();
            }
        }
    }
?>
