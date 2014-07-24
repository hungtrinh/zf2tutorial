<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Form\AlbumForm;

class AlbumController extends AbstractActionController
{
    /**
     * @var Album\Model\AlbumTable
     */
    protected $albumTable;

    /**
     * List all album
     * 
     * @link /album
     * @link /album/index
     */ 
    public function indexAction() 
    {
        $paginator = $this->getAlbumTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
        $paginator->setItemCountPerPage((int) $this->params()->fromQuery('size',10));

        return new ViewModel(array(
         // 'albums' => $this->getAlbumTable()->fetchAll(),
         'paginator' => $paginator
        ));;
    }

    /**
     * Add new album
     * 
     * @link /album/add
     */
    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    /**
     * Edit an album
     * 
     * @link /album/edit/{number} - number : album id
     */ 
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id',0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }

        // Get the album with the specified id. An exception is throw 
        // if it cannot be found, in which case go to the index page.
        try {
            $album = $this->getAlbumTable()->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'index'
            ));
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value','Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of album
                return $this->redirect()->toRoute('album');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    /**
     * Delete an album
     * 
     * @link /album/delete/{number} - number: album id
     */ 
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id',0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if($request->isPost()) {
            $del = $request->getPost('del','No');

            if ('Yes' === $del) {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            //Redirect to list of album
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id' => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    /**
     * Get Album Table instance
     * 
     * @return Album\Model\AlbumTable
     */ 
    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
}