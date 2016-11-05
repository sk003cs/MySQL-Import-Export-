<?php
    /**
    * @author  SENTHILKUMAR. R(sk003cs@gmail.com)
    * @version 0.0.1
    * @description Export and Import MySQL database
    * @copyright CS-Rockz
   */
    
    require_once("config.php");
    require_once("ie.php");
    
    define("I_SUCCEED", "Imported successfully<br/>");
    define("I_FAILED", "Failed to import<br/>");
    define("E_SUCCEED", "Exported successfully<br/>");
    define("E_FAILED", "Failed to export<br/>");
    
    $sqlE = new IE_UTILS(L_DB_HOST, L_DB_USER, L_DB_PASSWORD, L_DB_DATABASE_NAME);
    try {
        $exportedFilePath = $sqlE->export();
        if(empty(trim($exportedFilePath))) 
            echo E_FAILED;
         else
            echo E_SUCCEED;
    } catch(Exception $e) {
        echo $e->getMessage();
    }
    
    
    $sqlI = new IE_UTILS(R_DB_HOST, R_DB_USER, R_DB_PASSWORD, R_DB_DATABASE_NAME);
    try {
        if($sqlI->import($exportedFilePath))
            echo I_SUCCEED;
        else
            echo I_FAILED;
    } catch(Exception $e) {
        echo $e->getMessage();
    }
?>
