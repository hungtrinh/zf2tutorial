<?php
namespace TodoTest\Model;
use PHPUnit_Framework_TestCase;
use Todo\Model\Todo;

class TodoTest extends PHPUnit_Framework_TestCase
{
    public function testContructorRequiredParamWillFiled()
    {
        $todo = new Todo('sua ban vao luc 3 h');
        $this->assertEquals('sua ban vao luc 3 h', $todo->getTitle());
        $this->assertEquals(0, $todo->getId());
        $this->assertEquals(0, $todo->getComplete());
    }

    /**
     * @expectedException UnexpectedValueException 
     * @expectedExceptionMessage please set complete value in range [1..100]
     */
    public function testSetCompleteNotOver100()
    {
        $todo = new Todo('sua ban ghe');
        $todo->setComplete(101);
    }

    /**
     * @expectedException UnexpectedValueException 
     * @expectedExceptionMessage please set complete value in range [1..100]
     */
    public function testSetCompleteCannotInjectNegativeNumber()
    {
        $todo = new Todo('sua ban ghe');
        $todo->setComplete(-1);
    }
}