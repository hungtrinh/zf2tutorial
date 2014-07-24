<?php

namespace AlbumTest\Model;

use Album\Model\AlbumTable;
use Album\Model\Album;

use PHPUnit_Framework_TestCase;
use Zend\Db\ResultSet\ResultSet;

class AlbumTableTest extends PHPUnit_Framework_TestCase 
{
    public function testFetchAllReturnAllAlbum()
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
        $albumTableMapper = new AlbumTable($mockTableGateway);
        $this->assertSame($resultSet, $albumTableMapper->fetchAll());
    }

    public function testCanRetrieveAnAlbumById()
    {
        $id = 123;
        $album = new Album();
        $album->exchangeArray(array(
            'id' => $id,
            'artist' => 'hung trinh',
            'title' => 'Nhung tam long cao ca'
        ));
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Album());
        $resultSet->initialize(array($album));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway', 
            array('select'), 
            array(), 
            '',
            false
        );

        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => $id))
            ->will($this->returnValue($resultSet));

        $albumTable = new AlbumTable($mockTableGateway);
        $this->assertSame($album, $albumTable->getAlbum($id));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Could not find row 123
     */ 
    public function testExceptionIsThrowWhenGettingNonExistentAlbum()
    {
        $id = 123;
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Album());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway', 
            array('select'), 
            array(), 
            '', 
            false
        );

        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => $id))
            ->will($this->returnValue($resultSet));

        $albumTable = new AlbumTable($mockTableGateway);
        $albumTable->getAlbum($id);
    }

    public function testCanDeleteAnAlbumByItsId()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway', 
            array('delete'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('delete')
            ->with(array('id'=>123));

        $albumTable = new AlbumTable($mockTableGateway);
        $albumTable->deleteAlbum(123);
    }

    public function testSaveAlbumWillInsertNewAlbumsIfTheyDontAlreadyHaveAnId()
    {
        $albumData = array(
            'title' => "How to stop worring and start living",
            'artist' => "Dale Carnegie"
        );
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('insert'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->any())
            ->method('insert')
            ->with($albumData);


        $albumTable = new AlbumTable($mockTableGateway);

        $album = new Album();
        $album->exchangeArray($albumData);
        $albumTable->saveAlbum($album);

        $albumDataEmptyId = $albumData;
        $albumDataEmptyId['id'] = '';
        $album->exchangeArray($albumDataEmptyId);
        $albumTable->saveAlbum($album);

        $albumDataWithIdZero = $albumData;
        $albumDataWithIdZero['id'] = 0;
        $album->exchangeArray($albumDataWithIdZero);
        $albumTable->saveAlbum($album);
    }

    public function testSaveAlbumWillUpdateExistingAlbumsIfTheyAlreadyHaveAnId()
    {
        $albumData = array(
            'id' => 123,
            'title' => 'How To Win Friends and Influence People',
            'artist' => "Dale Carnegie"
        );
        $album = new Album();
        $album->exchangeArray($albumData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Album());
        $resultSet->initialize(array($album));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('update','select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id'=>123))
            ->will($this->returnValue($resultSet));

        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with(
                array(
                    'title' => 'How To Win Friends and Influence People',
                    'artist' => "Dale Carnegie"
                ),
                array(
                    'id' => 123
                )
            );

        $albumTable = new AlbumTable($mockTableGateway);
        
        $albumTable->saveAlbum($album);
    }
}
