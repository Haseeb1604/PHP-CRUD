<?php
    class Database{
        private $host;
        private $dbusername;
        private $dbpassword;
        private $dbname;

        protected function connect($host , $dbusername , $dbpassword, $dbname) {
            $this->host = $host;
            $this->dbusername = $dbusername;
            $this->dbpassword = $dbpassword;
            $this->dbname = $dbname;

            $conn = new PDO("mysql:host=".$this->host, $this->dbusername,$this->dbpassword);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $conn->exec("CREATE DATABASE IF NOT EXISTS $this->dbname");
            $conn->exec("use $this->dbname");

            return $conn;
        }

        public function createTable($table, $Query){
            $sql = "CREATE TABLE IF NOT EXISTS ".$table."(".$Query.");";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
    }

    class Query extends Database {
        private $conn;

        function __construct($host = 'localhost' , $dbusername = 'root', $dbpassword = '', $dbname = 'crud', ){
            $this->conn = $this->connect($host , $dbusername, $dbpassword, $dbname);
        }
        
        // Get Data
        public function getData($table, $condition_arr = [], $field = "*", $logic = "AND", $order_by_field="", $order_by_type="", $limit=""){
            $sql = "SELECT $field FROM $table";
            if(count($condition_arr)!==0){
                $c = count($condition_arr);
                $i = 1;
                $sql.= " WHERE ";
                foreach($condition_arr as $key => $val){
                    if($i !== $c){
                        $sql  .= $key . ' = :' . $key. " ". $logic." ";
                    }else{
                        $sql  .= $key . ' = :' . $key. " ";
                    }
                    $i++;
                }
            }else{
                $sql .= " WHERE 1 ";
            }

            if($order_by_field!==''){ 
                $sql.=" order by $order_by_field $order_by_type "; 
            }
            if($limit!==''){ 
                $sql.=" LIMIT $limit "; 
            }

            $stmt = $this->conn->prepare($sql);
            
            if(count($condition_arr)!==0){
                $stmt->execute($condition_arr);
            }else{
                $stmt->execute();
            }
            
            if($stmt->rowCount()>0){
                $arr = array();
                while($row = $stmt->fetch()){
                    $arr[] = $row;
                }
                return $arr;
            }else{
                return 0;
            }
        }
        
        // Insert Data
        public function insertData($table, $condition_arr){
            $has = $this->getData($table, ['name'=>$condition_arr['name']]);
            if($condition_arr!=='' && $has===0){
                foreach($condition_arr as $key=>$val){
                    $fieldArr[] = $key;
                }
                $field = implode(',',$fieldArr);
                $value = implode(',:',$fieldArr);
                $value = ":". $value;

                $sql = "INSERT INTO $table($field) Value($value);";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($condition_arr);
            }
        }

        // Delete Data
        public function deleteData($table, $condition_arr, $logic = 'AND'){
            if($condition_arr!==''){
                $condition = ' ';
                
                $c = count($condition_arr);
                $i = 1;

                foreach($condition_arr as $key => $val){
                    if($i !== $c){
                        $condition  .= $key . ' = :' . $key. " ". $logic." ";
                    }else{
                        $condition  .= $key . ' = :' . $key. " ";
                    }
                    $i++;
                }

                $sql = "DELETE FROM $table WHERE $condition;";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($condition_arr);
            }
        }

        // Update Data
        public function updateData($table, $condition_arr, $where_field, $where_value){
            $has = $this->getData($table, [$where_field=>$where_value]);
            if($condition_arr!=='' && ($has!==0)){
                $condition = ' ';
                
                $c = count($condition_arr);
                $i = 1;

                foreach($condition_arr as $key => $val){
                    if($i !== $c){
                        $condition  .= $key . ' = :' . $key. ", ";
                    }else{
                        $condition  .= $key . ' = :' . $key. " ";
                    }
                    $i++;
                }

                $sql = "UPDATE $table SET $condition WHERE $where_field = :$where_field"."2 ;";
                
                $stmt = $this->conn->prepare($sql);
                $where_field = $where_field."2";
                $stmt->execute(array_merge($condition_arr, [$where_field=>$where_value]));
            }
        }

        public function get_safe_string($str){
            if($str!==''){
                return trim($str);
            }
        }


    }


?>