<?php
/**
 * @author: Facundo Capua
 *        Date: 5/15/12
 */
class BaseDbCollection extends BaseCollection
{
    protected $_query = null;
    protected $_logicDeletion = true;


    protected $_table = null;
    protected $_singleClass = null;

    public function __construct()
    {
        $this->_query = new DatabaseSelect($this->_table);

        if($this->_logicDeletion){
            $this->_query->where('main.status > -1');
        }
    }

    public function getQuery()
    {
        return $this->_query;
    }

    public function load()
    {
        $recordset = $this->getQuery()->load();
        foreach($recordset as $record){
            $item = new $this->_singleClass($record);
            $this->add($item);
        }

        return $this;
    }
}
