<?php
class BasicUser extends BaseRecord
{
    protected $_id = null;
    protected $_firstname = null;
    protected $_lastname = null;
    protected $_email = null;
    protected $_createdAt = null;
    protected $_updatedAt = null;

    /**
     * @return the $_id
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param field_type $_id
     */
    public function setId($_id)
    {
        $this->_id = $_id;
    }

    /**
     * @return the $_firstname
     */
    public function getFirstname()
    {
        return $this->_firstname;
    }

    /**
     * @param field_type $_firstname
     */
    public function setFirstname($_firstname)
    {
        $this->_firstname = $_firstname;
    }

    /**
     * @return the $_lastname
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * @param field_type $_lastname
     */
    public function setLastname($_lastname)
    {
        $this->_lastname = $_lastname;
    }

    /**
     * @return the $_email
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param field_type $_email
     */
    public function setEmail($_email)
    {
        $this->_email = $_email;
    }
	
	 /**
     * @return the $_phone
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @param field_type $_phone
     */
    public function setPhone($_phone)
    {
        $this->_phone = $_phone;
    }

    /**
     * @return the $_createdAt
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * @param field_type $_createdAt
     */
    public function setCreatedAt($_createdAt)
    {
        $this->_createdAt = $_createdAt;
    }

    /**
     * @return the $_updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->_updatedAt;
    }

    /**
     * @param field_type $_updatedAt
     */
    public function setUpdatedAt($_updatedAt)
    {
        $this->_updatedAt = $_updatedAt;
    }
}