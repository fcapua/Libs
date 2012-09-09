<?php
/**
 * @author: Facundo Capua
 *        Date: 7/5/12
 */
class BaseSortedCollection extends BaseDbCollection
{
    public function moveUp(BaseRecord $object, $save = true)
    {
        $previous = $this->getPrevious($object);
        if($previous){
            $new_order = (int) $previous->getOrder();
            $previous->setOrder($object->getOrder());
            $object->setOrder($new_order);

            if($save){
                $previous->save();
                $object->save();
            }
        }
    }

    public function moveDown(BaseRecord $object, $save = true)
    {
        $next = $this->getNext($object);
        if($next){
            $new_order = (int) $next->getOrder();
            $next->setOrder($object->getOrder());
            $object->setOrder($new_order);

            if($save){
                $next->save();
                $object->save();
            }
        }
    }

    public function getPrevious(BaseRecord $object)
    {
        $query = new DatabaseSelect($this->_table);
        $query->where('main.status > -1')
                ->where('main.order < '.$object->getOrder())
                ->order('main.order', DatabaseSelect::ORDER_DIRECTION_DESC)
                ->setPageNumber(1)
                ->setPageSize(1);
        $return = $query->load();
        if($return){
            $obj = new $this->_singleClass($return[0]);

            return $obj;
        }

        return null;
    }

    public function getNext(BaseRecord $object)
    {
        $query = new DatabaseSelect($this->_table);
        $query->where('main.status > -1')
            ->where('main.order > '.$object->getOrder())
            ->order('main.order', DatabaseSelect::ORDER_DIRECTION_ASC)
            ->setPageNumber(1)
            ->setPageSize(1);
        $return = $query->load();
        if($return){
            $obj = new $this->_singleClass($return[0]);

            return $obj;
        }

        return null;
    }

    public function getFirstSorted()
    {
        $query = new DatabaseSelect($this->_table);
        $query->where('main.status > -1')
            ->order('main.order', DatabaseSelect::ORDER_DIRECTION_ASC)
            ->setPageNumber(1)
            ->setPageSize(1);
        $return = $query->load();
        if($return){
            $obj = new $this->_singleClass($return[0]);

            return $obj;
        }

        return null;
    }

    public function getLastSorted()
    {
        $query = new DatabaseSelect($this->_table);
        $query->where('main.status > -1')
            ->order('main.order', DatabaseSelect::ORDER_DIRECTION_DESC)
            ->setPageNumber(1)
            ->setPageSize(1);
        $return = $query->load();
        if($return){
            $obj = new $this->_singleClass($return[0]);

            return $obj;
        }

        return null;
    }
}
