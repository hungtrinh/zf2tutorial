<?php
namespace Album\Model;

use Album\Model\Album;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

/**
 * Album table mapper between database persit and remote system 
 */
class AlbumTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated=false)
    {
        if (!$paginated) {
            return $this->tableGateway->select();    
        }
        // create new select object for the table album
        $select = new Select($this->tableGateway->getTable());

        // create a new result set based on the Album entity
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Album());
        // create a new pagination adapter object
        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $resultSetPrototype
        );
        return new Paginator($paginatorAdapter);
    }

    public function getAlbum($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id'=> $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = array(
            'artist' => $album->artist,
            'title' => $album->title,
        );

        $id = (int) $album->id;
        if (0 === $id) {
            $this->tableGateway->insert($data);
            return null;
        } 

        if (!$this->getAlbum($id)) {
            throw new \Exception('Album id does not exist');
        }
        $this->tableGateway->update($data, array('id' => $id));
        return null;
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id'=> (int)$id));
    }
}