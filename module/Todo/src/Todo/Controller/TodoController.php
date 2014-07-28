<?php
namespace Todo\Controller;
// use Zend\View\Model\ViewModel;
use Todo\Model\TodoMapper;
use Zend\Mvc\Controller\AbstractActionController;

class TodoController extends AbstractActionController
{
    /**
     * @var Todo\Model\TodoMapper
     */ 
    protected $todoMapper = null;

    /**
     * @link /todo
     */
    public function indexAction()
    {
        $paginator = $this->getTodoMapper()->findAll(true);
        return array(
            'paginator' => $paginator
        );
    }

    public function addAction()
    {
    }

    public function editAction()
    {

    }

    public function deleteAction() 
    {
        
    }

    /**
     * Gets the value of todoMapper.
     *
     * @return Todo\Model\TodoMapper
     */
    public function getTodoMapper()
    {
        if (null === $this->todoMapper) {
            $sm = $this->getServiceLocator();
            $this->todoMapper = $sm->get('Todo\Model\TodoMapper');
        }
        return $this->todoMapper;
    }
}