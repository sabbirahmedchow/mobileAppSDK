<?php

class SimpleConfig implements ArrayAccess, Countable, IteratorAggregate
{

    protected static $_instance = null;

    protected static $_configFile = '';

    protected $_values = array();

    public static function getInstance() 
    {
        if (self::$_instance === null) {
            $c = __CLASS__;
            self::$_instance = new $c;
        } //if
        
        return self::$_instance;
    } // getInstance()

    public static function setFile($filePath) 
    {
        /* make sure instance doesn't exist yet */
        if (self::$_instance !== null) {
            throw new Exception('You need to set the path before calling '. __CLASS__ .'::getInstance() method', 0);
        } else {
            self::$_configFile = $filePath;
        } //if
    } // setFile()

    protected function __construct()
    {
        
        $values = @include( self::$_configFile );
        if (is_array($values)) {
            $this->_values = &$values;
        } //if
        
    } // __construct()

    final protected function __clone() { 
        // no cloning allowed
    } // __clone()

    public function count() 
    {
        return sizeof($this->_values);
    } // count()

    public function offsetExists($offset) 
    {
        return key_exists($offset, $this->_values);
    } // offsetExists()

    public function offsetGet($offset) 
    {
        return $this->_values[$offset];
    } // offsetGet()

    public function offsetSet($offset, $value) 
    {
        $this->_values[$offset] = $value;
    } // offsetSet()

    public function offsetUnset($offset) 
    {
        unset($this->_values[$offset]);
    } // offsetUnset()

    public function getIterator() 
    {
        return new ArrayIterator($this->_values);
    } // getIterator()

    public function __set($key, $value) 
    {
        $this->_values[$key] = $value;
    } // __set()

    public function __get($key) 
    {
        return $this->_values[$key];
    } // __get()
    
} // SimpleConfig class