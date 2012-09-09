<?php
/**
 * 
 * Abstrac class to define base record methods
 * @author Facundo Capua <facundocapua@gmail.com>
 *
 */
abstract class BaseRecord extends  BaseObject
{

    public function unserialize($serialized_object = null)
    {
        if($serialized_object !== null){
            $values = unserialize($serialized_object);
            $this->_data = $this->_orginalData = $values;
        }
    }

    public function serialize()
    {

        return serialize($this->_data);
    }

    public function getUniqueId()
    {
        return $this->_data[$this->unique];
    }

    
    public function isNew()
    {
        return !$this->hasId();
    }
    
    public function save()
    {
        $this->_beforeSave();

        $data = array();
        foreach($this->_data as $property=>$value){
            if(
                in_array($property, $this->_persistData)
                && ($this->isNew() || $this->hasDataChanged($property))
            ){
                $data[$property] = $value;
            }
        }
        if(!empty($data) || $this->getForceSave()){
            if(!$this->isNew()){
                $data[$this->unique] = $this->_data[$this->unique];
            }

            $query = DatabaseHelper::buildQuery($this->tableName, $data, ($this->isNew() ? DatabaseHelper::INSERT_QUERY : DatabaseHelper::UPDATE_QUERY), $this->unique);
            Database::getInstance()->query($query);

            if($this->isNew()){
                $aux = Database::getInstance()->query("select last_insert_id() id");
                $this->_data[$this->unique] = $aux[0]['id'];
                $this->_orginalData = $this->_data;
            }

            $this->setForceSave(false);
        }

        $this->_afterSave();
    }

    public function load($unique)
    {
        $query = "SELECT * FROM ".$this->tableName." WHERE ".$this->unique." = ".$unique;
        if($r = Database::getInstance()->query($query)){
            $this->_data = $this->_orginalData = $r[0];
        }

        return $this;
    }

    public function loadDefaultValues()
    {
        $query = "SELECT " . Database::getInstance()->listFields($this->tableName);
        if($r = Database::getInstance()->query($query)){
            $this->_data = $this->_orginalData = $r[0];
        }

        return $this;
    }

    public function delete()
    {
        if(!empty($this->_data[$this->unique])){
            $query = "DELETE FROM ".$this->tableName." WHERE ".$this->unique." = ".$this->_data[$this->unique];
            Database::getInstance()->query($query);
        }

    }

    public function update()
    {
        $r = Database::getInstance()->query('SELECT * FROM '.$this->tableName.' WHERE '.$this->unique.' = '.$this->{'_'.$this->unique});
        $this->hydrate($r[0]);
    }

    protected function _beforeSave()
    {
    }

    protected function _afterSave()
    {
    }

}