<?php namespace Controllers;

use Models\Brokers\HotspotBroker;
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
        $hotspots = (new HotspotBroker())->getHotspots(Session::getInstance()->read('id'));
//        var_dump($hotspots);
//        exit;
        return $this->render("map", [
            'title' => 'Map',
            'hotspots' => $this->json($hotspots)
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