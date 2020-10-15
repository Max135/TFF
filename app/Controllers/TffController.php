<?php namespace Controllers;

use Zephyrus\Application\Session;
use Zephyrus\Network\Response;

class TffController extends Controller
{

    public function initializeRoutes()
    {
        // TODO: Implement initializeRoutes() method.
    }

    public function before(): ?Response
    {
        if (Session::getInstance()->has('id') && Session::getInstance()->read('id') != null) {
            return parent::before();
        }

        return $this->redirect('/login');
    }
}