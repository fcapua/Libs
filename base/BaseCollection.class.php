<?php
/**
 * @author: Facundo Capua
 *        Date: 5/15/12
 */
class BaseCollection implements Iterator
{
    protected $_collection = array();
    protected $_dataLoaded = false;

    public function __construct($collection)
    {
        if (!empty($collection) && is_array($collection)) {
            foreach($collection as $item){
                $this->add($item);
            }

            $this->_dataLoaded = true;
        }
    }

    public function getFirst()
    {
        $this->rewind();

        return $this->current();
    }

    public function add($item)
    {
        if($item instanceof BaseRecord){
            $this->_collection[] = $item;

            return true;
        }

        return false;
    }

    public function rewind()
    {
        reset($this->_collection);
    }

    public function current()
    {
        $var = current($this->_collection);
        return $var;
    }

    public function key()
    {
        $var = key($this->_collection);
        return $var;
    }

    public function next()
    {
        $var = next($this->_collection);
        return $var;
    }

    public function valid()
    {
        $key = key($this->_collection);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

    public function size()
    {
        return sizeof($this->_collection);
    }
}
