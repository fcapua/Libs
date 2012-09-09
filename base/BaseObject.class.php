<?php
/**
 * @author: Facundo Capua
 *        Date: 5/19/12
 */
class BaseObject
{
    protected $_data = array();
    protected $_orginalData = array();
    protected $_hasChanged = false;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->_data = $this->_orginalData = $data;
        }
    }

    public function __call($name, $arguments)
    {
        if(substr($name, 0, 2) == 'is'){
            $property = StringHelper::uncamelize(substr($name, 2));
            if(isset($arguments[0])){
                $identical = isset($arguments[1]) ? (boolean) $arguments[1] : false;
                return $this->_isValue($property, $arguments[0], $identical);
            }
        }else{
            $prefix   = substr($name, 0, 3);
            $property = StringHelper::uncamelize(substr($name, 3));

            switch ($prefix) {
                case 'set':
                    if(!isset($arguments[0])){
                        throw new Exception('Value needed to set property');
                    }
                    $this->_setValue($property, $arguments[0]);

                    return $this;

                    break;

                case 'get':
                    return $this->_getValue($property);
                    break;

                case 'has':
                    return $this->_hasValue($property);
                    break;
            }
        }
    }

    public function hasDataChanged($name)
    {
        return  $this->_data[$name] !== $this->_orginalData[$name];
    }

    public function setData($data)
    {
        foreach($data as $name => $value){
            $this->_data[$name] = $value;
        }

        return $this;
    }

    protected function _setValue($name, $value)
    {
        if (isset($this->_data[$name])) {
            $this->_data[$name] = $value;
            $this->_hasChanged  = true;
        }
    }

    protected function _getValue($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    protected function _hasValue($name)
    {
        return !empty($this->_data[$name]);
    }

    protected function _isValue($name, $value, $identical = false)
    {
        if($identical){
            return $this->_data[$name] === $value;
        }else{
            return $this->_data[$name] == $value;
        }

    }
}
