<?php
namespace TodoTest\Model;

use PHPUnit_Framework_Testcase;
use Zend\Db\ResultSet\ResultSet;
use Todo\Model\TodoMapper;

class TodoMapperTest extends PHPUnit_Framework_Testcase
{
    public function testFindAllReturnAllRecordAsResultSet()
    {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with()
            ->will($this->returnValue($resultSet));

        $todoMapper = new TodoMapper($mockTableGateway);
        $actual =  $todoMapper->findAll(false);
        $this->assertInstanceOf('Zend\Db\ResultSet\ResultSet', $actual);
        $this->assertSame($resultSet,$actual);
    }
}