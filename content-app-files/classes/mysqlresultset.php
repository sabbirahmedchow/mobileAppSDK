<?php

class MySqlResultSet implements Iterator
{
    private $query;
    private $result;
    private $index = 0;
    private $num_rows = 0;
    private $row = false;
    private $type;

    const DATA_OBJECT = 1;

    const DATA_NUMERIC_ARRAY = 2;

    const DATA_ASSOCIATIVE_ARRAY = 3;

    const DATA_ARRAY = 4;

    public function __construct($query, $data_type=MySqlResultSet::DATA_OBJECT, 
                                $link=false) 
    {
        if ($link) $this->result = @mysql_query($query, $link);
        else $this->result = @mysql_query($query);

        if (!$this->result) {
            throw new Exception(mysql_error());
        }
        
        if (!is_resource($this->result) 
            || get_resource_type($this->result) != 'mysql result') {
            throw new Exception("Query does not return an mysql result resource.");
        }
        
        $this->query = $query;
        $this->num_rows = mysql_num_rows($this->result);
        $this->type = $data_type;
    }

    public function __destruct()
    {
        if (is_resource($this->result) 
            && get_resource_type($this->result) == 'mysql result') {
            mysql_free_result($this->result);   
        }
    }
    
    private function fetch()
    {
        if ($this->num_rows > 0) {
            switch ($this->type) {
                case MySqlResultSet::DATA_NUMERIC_ARRAY: 
                    $func = 'mysql_fetch_row';
                    break;
                case MySqlResultSet::DATA_ASSOCIATIVE_ARRAY: 
                    $func = 'mysql_fetch_assoc';
                    break;
                case MySqlResultSet::DATA_ARRAY: 
                    $func = 'mysql_fetch_array';
                default: 
                    $func = 'mysql_fetch_object';
                    break;
            }
            
            $this->row = $func($this->result);
            $this->index++;
        }
    }
    
    public function getResultResource()
    {
        return $this->result;
    }
    
    public function isEmpty()
    {
        if ($this->num_rows == 0) return true;
        else return false;
    }

    public function rewind() 
    {
        if ($this->num_rows > 0) {
            mysql_data_seek($this->result, 0);
            $this->index = -1;  // fetch() will increment to 0
            $this->fetch();
        }
    }

    function current() 
    {
        return $this->row;
    }

    function key() 
    {
        return $this->index;
    }

    function next() 
    {
        $this->fetch();
    }

    function valid() 
    {
       if ($this->row === false) return false;
       else return true;
    }
}
?>
