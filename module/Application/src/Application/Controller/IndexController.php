<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug as ZendDebugger;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
     //    ZendDebugger::dump(getenv('APPLICATION_ENV'));
    	// $viewModel = new ViewModel();
     //    $viewModel->setTerminal(true);
     //    return $viewModel;
    }

    public function albumAction() 
    {
    	$sm = $this->getServiceLocator();
		$albumTable = $sm->get('AlbumTableGateway');

    	$viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->albums = $albumTable->select();
        return $viewModel;	
    }

    public function sitemapAction()
    {

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
