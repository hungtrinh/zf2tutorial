<?php
namespace Todo\Model;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Todo\Model\Todo;

class TodoMapper
{
    /**
     * @var Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function findAll($hasPaginator=false) {
        if (false === $hasPaginator) {
            return $this->getTableGateway()->select();
        }
        $select = new Select($this->getTableGateway()->getTable());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Todo());
        return new Paginator(new DbSelect(
            $select,
            $this->getTableGateway()->getAdapter(),
            $resultSetPrototype
        ));
    }

    /**
     * Gets the value of tableGateway.
     *
     * @return Zend\Db\TableGateway\TableGateway
     */
    protected function getTableGateway()
    {
        return $this->tableGateway;
    }
}