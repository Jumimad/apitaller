<?php
    /**
     * TO FIX DATABASE CHARSET EXECUTE:
     * ALTER TABLE table_name CONVERT TO CHARACTER SET utf8
     * SET NAMES UTF8
     * */
    class DatabaseConnection
        {
            private $user = null;
            private $dbname = null;
            private $password = null;
            private $host = 'localhost';
            private $charset = 'utf8mb4';
            
            # Create a singleton to store the connection for reuse
            private static $singleton,
                           $con;
            # save connection to singleton and return itself (the full object)
            public function __construct()
               {
                    # If your singleton is not set
                    if(!isset(self::$singleton))
                        # assign it this class
                        self::$singleton = $this;
                    # return this class
                    return self::$singleton;
               }
            # This is a connection method because your __construct
            # is not able to return the $pdo connection
            public function connection()
                {
                        include ROOT_PATH.'/Config.php';
                
                        $this->user = $config['user'];
                        $this->dbname = $config['dbname'];
                        $this->host = $config['host'];
                        $this->password = $config['password'];
                        $this->charset = $config['charset'];
                        
                    # In the connection, you can assign the PDO to a static
                    # variable to send it back if it's already set
                    if(self::$con instanceof \PDO)
                        return self::$con;
                    # If not already a PDO connection, try and make one
                    try {
                            # PDO settings you can apply at connection time
                            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
                            # Assign your PDO Conneciton here.
                            self::$con =  new PDO("mysql:host=".$this->host.";dbname=".$this->dbname,$this->user,$this->password,$opts);
                            //var_dump("mysql:host=".$this->host.";dbname=".$this->dbname,$this->user,$this->password);
                            //var_dump(self::$con);
                            # Return the connection
                            return self::$con;
                        }
                    catch (PDOException $e){
                            exit('Database error');
                        }   
                }
                
            public function delete($Table=false, $DataArray=array()){
                
                $fields = $fields2 = array();
                foreach($DataArray as $field=>$value){
                   $fields[] = $field.'= :'.$field;
                   $fields2[] = $field;
                }
                
                $sql = "DELETE FROM $Table WHERE ".implode(' and ',$fields)." ";
                $stmt =  self::$con->prepare($sql);
                foreach($fields2 as $field){
                    $stmt->bindParam(':'.$field, $DataArray[$field]);
                }
                
                 $stmt->execute();
                 //self::$con = null;
            }
            
            
             public function updateSanitized($Table=false, $setData=array() ,$DataArray=array()){
                
                $fields = $fields2 = array();
                foreach($DataArray as $field=>$value){
                   $fields[] = $field.'= :'.$field;
                   $fields2[] = $field;
                }
                
                
                $fields3 = $fields4 = array();
                foreach($setData as $field_=>$value_){
                   $fields3[] = $field_.'= :'.$field_;
                   $fields4[] = $field_;
                }
                
                $sql = "UPDATE $Table SET ".implode(', ',$fields3)." WHERE ".implode(' and ',$fields)." ";
          
                $stmt =  self::$con->prepare($sql);
                
                
                foreach($fields4 as $field_){
                    $stmt->bindParam(':'.$field_, $setData[$field_]);
                }
                
                foreach($fields2 as $field__){
                    $stmt->bindParam(':'.$field__, $DataArray[$field__]);
                }
                
                $stmt->execute();
                //self::$con = null;
            }
            
             public function query($sql, $return=false) {
    
                $STH = self::$con->prepare($sql);
                ////$this->closeConnection();
                $Re = $STH->execute();
                //$this->closeConnection();
                
                if($return){
                   return $Re;
                }else{
                   $Re;
                }
                //self::$con = null;
                //$this->DBH = null;
            }
        
            public function insert($sql, $return=false) {
             
                $STH = self::$con->prepare($sql);
                ////$this->closeConnection();
                
                $Re = $STH->execute();
                $id = self::$con->lastInsertId();
                //$this->closeConnection();
                if($return){
                   //return $Re;
                   return $id;
                }else{
                   $Re;
                }
                //$this->DBH = null;
                //self::$con = null;
                
            }
            
            public function insertSanitized($Table=false, $DataArray=array(), $debug=false, $onUpdate=false, $ignore=false){
                
                $fields = array();
                foreach($DataArray as $field=>$value){
                   $fields[] = $field;
                }
                
                $stringQuery = "(".implode(', ',$fields).") VALUES (:".implode(', :',$fields).")";
                if($ignore){
                    $sentencia =self::$con->prepare("INSERT IGNORE INTO ".$Table." ".$stringQuery." ".$onUpdate);
                }else{
                    $sentencia =self::$con->prepare("INSERT INTO ".$Table." ".$stringQuery." ".$onUpdate);
                }
                
                
                foreach($fields as $field){
                    //var_dump($DataArray[$field]);
                    //if(isset($DataArray[$field]) && !is_array($DataArray[$field])){
                        $sentencia->bindParam(':'.
                        $field, 
                        $DataArray[$field]);    
                    //}
                    
                    
                }
                $sentencia->execute();
                
                if($debug){
                    var_dump("INSERT INTO ".$Table." ".$stringQuery." ");
                    $sentencia->debugDumpParams();
                }
                $id = self::$con->lastInsertId();
                //$this->closeConnection();
                //$this->DBH = null;
                //self::$con = null;
                return $id;
            }
            
            public function update($sql, $return=false) {
            
                $STH = self::$con->prepare($sql);
                ////$this->closeConnection();
                
                if($return){
                   return $STH->execute();
                }else{
                   $STH->execute();
                }
                //self::$con = null;
                //$this->DBH = null;
                //$this->closeConnection();
                
            }
            
            
            public function fetchAll($sql, $type2=false){
                
                $STH = self::$con->prepare($sql);
                $STH->execute();
                if($type2){
                    $Re = $STH->fetchAll($type2);
                }else{
                    $Re = $STH->fetchAll(PDO::FETCH_ASSOC);
                }
                
                /* Obtener todas las filas restantes del conjunto de resultados */
                //$this->closeConnection();
                //$this->DBH = null;
                //self::$con = null;
                return $Re;
            }
            
            
            
            public function fetchAllPrepare($sql, $type=1, $prepare=array()){
                
                $STH = self::$con->prepare($sql);
                $STH->execute($prepare);
                $Re = $STH->fetchAll();
                /* Obtener todas las filas restantes del conjunto de resultados */
                //$this->closeConnection();
                //$this->DBH = null;
                //self::$con = null;
                return $Re;
            }
        }