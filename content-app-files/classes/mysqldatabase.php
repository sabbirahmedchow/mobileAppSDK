<?php

class MySqlDatabase
{

    public $link;
    private $conn_str;
    private static $instance;
    
    const MYSQL_DATE_FORMAT = 'Y-m-d';
    const MYSQL_TIME_FORMAT = 'H:i:s';
    const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
    
    const INSERT_GET_AUTO_INCREMENT_ID = 1;
    const INSERT_GET_AFFECTED_ROWS = 2;

    private function __construct() {}

    public function connect($host, $user, $password, $database=false, $persistant=false)
    {
        if ($persistant) {
            $this->link = @mysql_pconnect($host, $user, $password);
        } else {
            $this->link = @mysql_connect($host, $user, $password);
        }
        
        if (!$this->link) 
        {
            throw new Exception('Unable to establish database connection: ' 
                                .mysql_error());
        }

        if ($database) $this->useDatabase($database);
        
        $version = mysql_get_server_info();
        $this->conn_str = "'$database' on '$user@$host' (MySQL $version)";
        
        return $this->link;
    }

    public function delete($query) 
    {
        return $this->updateOrDelete($query);
    }
    
    /**
     *  Get Connection String
     *
     *  Gets a string representing the connection.
     *
     *  @return string
     */
    public function getConnectionString() 
    {
        return $this->conn_str;
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new MySqlDatabase();
        }
        
        return self::$instance;
    }

    public function fetchOneFromEachRow($query)
    {
        $rval = array();
        
        foreach ($this->iterate($query, MySqlResultSet::DATA_NUMERIC_ARRAY) as $row) {
            $rval[] = $row[0];
        }

        return $rval;
    }

    public function fetchOneRow($query, $data_type=MySqlResultSet::DATA_OBJECT)
    {
        $result = new MySqlResultSet($query, $data_type, $this->link);
        $result->rewind();
        $row = $result->current();

        return $row;
    }

    public function fetchOne($query)
    {
        $result = new MySqlResultSet($query, MySqlResultSet::DATA_NUMERIC_ARRAY, 
                                     $this->link);
        $result->rewind();
        $row = $result->current();

        if (!$row) return false;
        else return $row[0];
    }

    public function importSqlFile($filename, $callback=false, $abort_on_error=true)
    {
        if ($callback && !is_callable($callback)) {
            throw new Exception("Invalid callback function.");
        }

        $lines = $this->loadFile($filename);
        
        $num_queries = 0;
        $sql_line = 0;
        $sql = '';
        $in_comment = false;
        
        foreach ($lines as $num => $line) {
            
            $line = trim($line);
            $num++;
            if (empty($sql)) $sql_line = $num;
            
            // ignore comments
            
            if ($in_comment) {
                $comment = strpos($line, '*/');
                
                if ($comment !== false) {
                    $in_comment = false;
                    $line = substr($line, $comment+2);
                } else {
                    continue;
                }
                
            } else {
                
                $comment = strpos($line, '/*');
                
                if ($comment !== false) {
                    
                    if (strpos($line, '*/') === false) {
                        $in_comment = true;
                    }
                    
                    $line = substr($line, 0, $comment);
                    
                } else {
                
                    // single line comments
                    
                    foreach (array('-- ', '#') as $chars) {
                        $comment = strpos($line, $chars);
                        
                        if ($comment !== false) {
                            $line = substr($line, 0, $comment);
                        }
                    }
                }
            }

            // check if the statement is ready to be queried
            
            $end = strpos($line, ';');
            
            if ($end === false) {
                $sql .= $line;
            } else {
                $sql .= substr($line, 0, $end);
                $result = $this->quickQuery($sql);
                $num_queries++;
                
                if (!$result && $abort_on_error) {
                    $file = basename($filename);
                    $error = mysql_error($this->link);
                    throw new Exception("Error in $file on line $sql_line: $error");
                }
                
                if ($callback) {
                    call_user_func($callback, $sql_line, $sql, $result);
                }
                
                $sql = '';  // clear for next statement
                
            }
        }
        
        return $num_queries;
    }

    public function isConnected()
    {
        if (!empty($this->link)) {
            return @mysql_ping($this->link);
        } else {
            return false;
        }
    }

    public function insert($query, $r_type=MySqlDatabase::INSERT_GET_AUTO_INCREMENT_ID) 
    {
        $r = $this->query($query);
        
        if ($r_type == MySqlDatabase::INSERT_GET_AFFECTED_ROWS) {
            return @mysql_affected_rows($this->link);
        } else {
            return @mysql_insert_id($this->link);
        }
    }
 
    public function smartInsert($table, $columns, $values)
    {
        if (empty($table) || !is_string($table)) {
            throw new Exception('The $table parameter must be specified as a string.');
        }
        
        $table_sql = '`' . @mysql_real_escape_string($table) . '`';
        $query = "INSERT INTO $table_sql ";
        
        // columns
        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }
        
        if (is_array($columns)) {
            foreach ($columns as &$col) {
                if (!is_string($col)) {
                    throw new Exception('The $columns parameter must be a string or an array of strings');
                }
                $col = @mysql_real_escape_string($col);
            }
            $column_sql = implode(',', $columns);
            $column_count = count($columns);
        } else {
            throw new Exception('The $columns parameter must be a string or an array of strings.');
        }
        
        try {
            $column_info = array();
            
            foreach ($this->iterate("SHOW COLUMNS FROM $table_sql") as $row) {
                $column_info[] = $row;
            }
        } 
        catch (Exception $e) {
            throw new Exception("Could not get column information for table $table_sql.");
        }
        
        $query .= "($column_sql) ";
        
        // values
        
        if (is_array($values)) {
            for ($i=0; $i < count($values); $i++) {
                $info = $column_info[$i];
                $value = $values[i];
                
                // Where the heck did I leave off?
            }
        } else {
            // TODO: if only 1 column, then this will work

            throw new Exception('The $values parameter must be a string or an array.');
        }
        
        if (isset($column_count) && $column_count <> $value_count) {
            throw new Exception("Column count ($column_count) does not match values count ($value_count).");
        }
        
        $query .= "VALUES ($value_sql) ";

        echo $query;
        
    }

    public function iterate($sql, $data_type=MySqlResultSet::DATA_OBJECT) 
    {
        return new MySqlResultSet($sql, $data_type, $this->link);
    }

    private function loadFile($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception("File does not exist: $filename");
        }
        
        $file = @file($filename, FILE_IGNORE_NEW_LINES);
        
        if (!$file) {
            throw new Exception("Could not open $filename");
        }
        
        return $file;
    }

    public function query($query) 
    {
        $r = @mysql_query($query, $this->link);

        if (!$r) {
            throw new Exception("Query Error: " . mysql_error());
        }
        
        return $r;
    }

    public function quickQuery($query)
    {
        $r = @mysql_query($query, $this->link);
        
        if (!$r) return false;
        if (is_resource($r)) mysql_free_result($r);

        return true;
    }

    public function update($query) 
    {
        return $this->updateOrDelete($query);
    }
    
    private function updateOrDelete($query)
    {
        $r = $this->query($query);
        return @mysql_affected_rows($this->link);
    }

    public function useDatabase($database) 
    {
        if (!@mysql_select_db($database, $this->link))
        {
            throw new Exception('Unable to select database: ' . mysql_error($this->link));
        }
    }
}



?>
