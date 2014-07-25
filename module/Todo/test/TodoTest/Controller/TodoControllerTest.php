<?php
namespace TodoTest\Controller;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class TodoControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * @var boolean
     */
    protected $traceError = true;

    public function setUp()
    {
        $applicationConfigPath = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . 
            DIRECTORY_SEPARATOR  ."config" . 
            DIRECTORY_SEPARATOR  . "application.config.php";
        
        $this->setApplicationConfig(
            include  $applicationConfigPath
        );
        parent::setup();
    }

    /**
     * Assert that the application route match used the given controller name
     *
     * @param  string $controller
     */
    public function assertParamEqualValue($paramName, $expectedValue)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $match      = $routeMatch->getParam($paramName);

        if ($expectedValue != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(sprintf(
                "Failed asserting param '$paramName' value \"%s\", actual '$paramName' value is \"%s\"",
                $expectedValue, $match
            ));
        }
        $this->assertEquals($expectedValue, $match);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/todo');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Todo');
        $this->assertControllerName('Todo\Controller\Todo');
        $this->assertControllerClass('TodoController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('todo');
    }

    public function testAddActionCanBeAccessed()
    {
        $this->dispatch('/todo/add');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Todo');
        $this->assertControllerName('Todo\Controller\Todo');
        $this->assertControllerClass('TodoController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('todo');
    }

    public function testEditActionCanBeAccessed()
    {
        $this->dispatch('/todo/edit/9');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Todo');
        $this->assertControllerName('Todo\Controller\Todo');
        $this->assertControllerClass('TodoController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('todo');
        $this->assertParamEqualValue('id',9);
    }

    public function testDeleteActionCanBeAccessed()
    {
        $this->dispatch('/todo/delete/9');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Todo');
        $this->assertControllerName('Todo\Controller\Todo');
        $this->assertControllerClass('TodoController');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('todo');
        $this->assertParamEqualValue('id',9);
    }
}