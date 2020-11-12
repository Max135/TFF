<?php namespace Controllers;

use Models\TableObject;
use phpDocumentor\Reflection\Types\Array_;
use Zephyrus\Application\Session;
use Zephyrus\Network\Response;

class TffController extends Controller
{

    public function initializeRoutes()
    {
        $this->get("/map", "renderMap");
        $this->get('/test', 'renderTest');
        $this->get('/hub', 'renderHub');
        $this->get('/friends', 'renderAcquaintances');
        $this->get('/options', 'renderHub');
        $this->get('/winds', 'renderWindsPage');
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

    public function renderMap() {
        $data = array();
        $data["date"] = '01:01:02';
        $data["startTime"] = '01:01:02';
        $data["endTime"] = '04:01:02';
        $data["pressure"] = '55';
        return $this->render("map", [
            'title' => 'Map',
            'table' => $this->buildProjectTable($data)
        ]);
    }

    public function renderWindsPage() {
        return $this->render('wind');
    }

    private function buildProjectTable($data) {
        return new TableObject([
            "Date",
            "Start Time",
            "End Time",
            "Pressure"], $data);
    }
}