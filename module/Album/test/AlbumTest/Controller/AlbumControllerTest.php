<?php

namespace AlbumTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null;

class AlbumControllerTest extends AbstractHttpControllerTestCase
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
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('fetchAll')
                        ->with(true)
                        ->will($this->returnValue(new Paginator(new Null(0))));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('album');
    }

    public function testAddActionCanBeAccessed()
    {
        $this->dispatch('/album/add');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('album');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('saveAlbum')
                        ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $postData = array(
            'id' => 0,
            'title'  => 'Nhan ban',
            'artist' => 'hung trinh',
        );
        $this->dispatch('/album/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/album');
    }

    public function testEditActionCanBeAccessed()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();
        $albumTableMock->expects($this->once()) 
                        ->method('getAlbum')
                        ->will($this->returnValue(new \Album\Model\Album()));
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album/edit/1');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('album');
        $this->assertParamEqualValue('id',1);
    }

    public function testEditActionRedirectToAddAlbumPageIfEmptyIdParam()
    {
        $this->dispatch('/album/edit');

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album/add');
    }

    public function testEditActionRedirectAfterNotFoundAlbum()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();
        $albumTableMock->expects($this->once()) 
                        ->method('getAlbum')
                        ->will($this->throwException(new \Exception));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album/edit/1');

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');
    }

    public function testDeleteActionCanBeAccessed()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();
        $albumTableMock->expects($this->once()) 
                        ->method('getAlbum')
                        ->will($this->returnValue(new \Album\Model\Album()));
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album/delete/1');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('album');
        $this->assertParamEqualValue('id',1);
    }

    public function testDeleteActionRedirectToPageListAlbumIfEmptyIdParam()
    {
        $this->dispatch('/album/delete');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');
    }

    public function testDeleteActionRedirectToPageListAlbumAfterConfirmDelAlbum()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())  
                        ->method('deleteAlbum')
                        ->with($this->equalTo(1))
                        ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album/delete/1','POST', array(
            'id' => '1',
            'del' => 'Yes'  
        ));

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');
    }
}