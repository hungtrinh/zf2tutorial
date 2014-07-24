<?php
namespace AlbumTest\Model;

use Album\Model\Album;
use PHPUnit_Framework_TestCase;

class AlbumTest extends PHPUnit_Framework_TestCase
{
    public function testAlbumInitialState()
    {
        $album = new Album();

        $this->assertNull(
            $album->artist,
            '"artist" should initially be null'
        );

        $this->assertNull(
            $album->id,
            '"id" should initially be null'
        );

        $this->assertNull(
            $album->title,
            '"title" should initially be null'
        );
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $album = new Album();
        $data = array(
            'id' => 1,
            'artist' => 'hung trinh',
            'title' => 'nhan van'
        );

        $album->exchangeArray($data);

        $this->assertSame($data['id'],$album->id);
        $this->assertSame($data['artist'],$album->artist);
        $this->assertSame($data['title'],$album->title);
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $album = new Album();
        $album->exchangeArray(array(
            'id' => 1,
            'artist' => 'hung trinh',
            'title' => 'nhan van 1'
        ));
        $album->exchangeArray(array());
        $this->assertNull($album->id);
        $this->assertNull($album->title);
        $this->assertNull($album->artist);
    }

    public function testArrayCopyReturnAnArrayWithPropertyValues()
    {
        $originData = array(
            'artist' => 'hungtd',
            'id' => 1,
            'title' => 'nhan tam'
        );
        $album = new Album();
        $album->exchangeArray($originData);
        $arrayCopy = $album->getArrayCopy();
        
        $this->assertEquals($originData, $arrayCopy);
    }
}