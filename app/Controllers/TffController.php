<?php namespace Controllers;

use Zephyrus\Application\Session;
use Zephyrus\Network\Response;

class TffController extends Controller
{

    public function initializeRoutes()
    {
        $this->get("/map", "showMap");
        $this->get('/test', 'renderTest');
        $this->get('/hub', 'renderHub');
        $this->get('/friends', 'renderAcquaintances');
        $this->get('/options', 'renderHub');
    }

//    public function before(): ?Response
//    {
//        if (Session::getInstance()->has('id') && Session::getInstance()->read('id') != null) {
//            return parent::before();
//        }
//
//        return $this->redirect('/login');
//    }

    public function renderAcquaintances()
    {
        return $this->render('acquaintances');
    }

    public function renderHub()
    {
        return $this->render('hub');
    }

    public function renderTest()
    {
        return $this->render('navbar');
    }

    public function showMap() {
        return $this->render("map", [
            'title' => 'Map'
        ]);
    }
}