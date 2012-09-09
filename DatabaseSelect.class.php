<?php
/**
 * @author: Facundo Capua
 *        Date: 5/19/12
 */
class DatabaseSelect
{
    const JOIN_TYPE_INNER = 'INNER';
    const JOIN_TYPE_LEFT  = 'LEFT';
    const JOIN_TYPE_RIGHT = 'RIGHT';

    const ORDER_DIRECTION_ASC  = 'ASC';
    const ORDER_DIRECTION_DESC = 'DESC';

    protected $_table = null;
    protected $_fields = array();
    protected $_conditions = array();
    protected $_joins = array();
    protected $_orders = array();
    protected $_pageSize = null;
    protected $_pageNumber = 1;

    protected $_pages = null;
    protected $_totalRecords = null;

    public function __construct($table = null)
    {
        if (empty($table)) {
            throw new Exception('Invalid table name');
        }

        $this->_table = $table;

        return $this;
    }

    public function field($name, $alias = null)
    {
        if (is_array($name)) {
            foreach ($name as $fieldName => $fieldAlias) {
                $fieldName = is_numeric($fieldName) ? $fieldAlias : $fieldName;
                $this->field($fieldName, $fieldAlias);
            }
        } else {
            $alias = empty($alias) ? $name : $alias;
            if (!isset($this->_fields[$name])) {
                if (!in_array($alias, $this->_fields)) {
                    $this->_fields[$name] = $alias;
                } else {
                    throw new Exception('A field with that alias already exists');
                }

            }
        }

        return $this;
    }

    public function where($conditions)
    {
        if (is_array($conditions)) {
            foreach ($conditions as $condition) {
                $this->where($condition);
            }
        } else {
            $this->_conditions[] = $conditions;
        }

        return $this;
    }

    public function join($table, $alias, $condition, $type = self::JOIN_TYPE_INNER)
    {
        $this->_joins[$table] = array(
            'table'     => $table,
            'alias'     => $alias,
            'condition' => $condition,
            'type'      => $type
        );

        return $this;
    }

    public function order($name, $direction = self::ORDER_DIRECTION_ASC)
    {
        $this->_orders[$name] = $direction;

        return $this;
    }

    public function setPageSize($pageSize)
    {
        $this->_pageSize = $pageSize;

        return $this;
    }

    public function setPageNumber($pageNumber)
    {
        $this->_pageNumber = $pageNumber;

        return $this;
    }

    public function getPagesCount()
    {
        return $this->_pages;
    }

    public function getTotalRecords()
    {
        return $this->_totalRecords;
    }


    public function load()
    {
        $query = $this->buildQuery();

        $recordset = Database::getInstance()->query($query);
        if ($this->_pageSize) {
            $rsTotal             = Database::getInstance()->query("select found_rows() total");
            $this->_totalRecords = $rsTotal[0]['total'];
            $this->_pages        = ceil($this->_totalRecords / $this->_pageSize);
        }

        return $recordset;
    }


    public function buildQuery()
    {
        $selectClause = $this->_buildSelectClause();
        $joinsClause  = $this->_buildJoinsClause();
        $whereClause  = $this->_buildWhereClause();
        $orderClause  = $this->_buildOrderClause();
        $limitClause  = $this->_buildLimitClause();

        $query = "SELECT " . (!empty($this->_pageSize) ? 'SQL_CALC_FOUND_ROWS' : '') . "
                    {$selectClause}
                  FROM {$this->_table} AS main
                  {$joinsClause}
                  {$whereClause}
                  {$orderClause}
                  {$limitClause}
                  ";

        return $query;
    }

    protected function _buildSelectClause()
    {
        $return = '*';
        if (!empty($this->_fields)) {
            $return = '';
            foreach ($this->_fields as $field => $alias) {
                $tableAlias = '';
                if (strpos($field, '.') !== false) {
                    $aux        = explode('.', $field);
                    $tableAlias = $aux[0].'.';
                    $field      = $aux[1];
                }
                $return .= $tableAlias.'`' . $field . '` AS `' . $alias . '`,';
            }
            $return = substr($return, 0, -1);
        }

        return $return;
    }

    protected function _buildJoinsClause()
    {
        $return = ' ';
        if (!empty($this->_joins)) {
            foreach ($this->_joins as $join) {
                $return .= $join['type'] . ' JOIN `' . $join['table'] . '` AS `' . $join['alias'] . '` ON ' . $join['condition'] . ' ';
            }
        }

        return $return;
    }

    protected function _buildWhereClause()
    {
        $return = ' ';
        if (!empty($this->_conditions)) {
            $return .= 'WHERE ';
            $isFirst = true;
            foreach ($this->_conditions as $condition) {
                if (false == $isFirst) {
                    $return .= ' AND ';
                } else {
                    $isFirst = false;
                }

                $return .= $condition;
            }
        }

        return $return;
    }

    protected function _buildOrderClause()
    {
        $return = ' ';
        if (!empty($this->_orders)) {
            $return .= 'ORDER BY ';
            foreach ($this->_orders as $field => $direction) {
                $return .= $field . ' ' . $direction . ',';
            }
            $return = substr($return, 0, -1);
        }

        return $return;
    }

    protected function _buildLimitClause()
    {
        $return = ' ';
        if (!empty($this->_pageSize)) {
            $return = " LIMIT " . (($this->_pageNumber - 1) * $this->_pageSize) . "," . $this->_pageSize;
        }

        return $return;
    }

}
