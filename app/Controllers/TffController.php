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
        $this->get('/mapOn/{coords}/{hotspotId}', "renderMapOnHotspot");
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
        return $this->render("map", [
            'title' => 'Map',
            'userId' => Session::getInstance()->read('id'),
            'mapWidth' => "col-12",
            'center' => [-72.88, 45.62],
            'hotspotInfo' => null
        ]);
    }

    public function renderMapOnHotspot($coords, $hotspotId) {
        $splitted = explode(",", $coords);
        $hotspotInfo = (new HotspotBroker())->getHotspotInfos($hotspotId);
        return $this->render("map", [
            'title' => 'Map',
            'userId' => Session::getInstance()->read('id'),
            'mapWidth' => "col-9",
            'center' => [floatval($splitted[0]), floatval($splitted[1])],
            'hotspotInfo' => $hotspotInfo
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