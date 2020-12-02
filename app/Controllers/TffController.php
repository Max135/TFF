<?php namespace Controllers;

use Models\Brokers\FishBroker;
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
        $this->get('/winds/{id}', 'renderWindsPage');
        $this->get('/store', 'renderStore');
        $this->get('/fish/{id}', 'renderFishPage');
        $this->get('/permissions', 'renderPermissionPage');
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
            'hotspotInfo' => null,
            'pressureList' => null
        ]);
    }

    public function renderMapOnHotspot($coords, $hotspotId) {
        $splitted = explode(",", $coords);
        return $this->render("map", [
            'title' => 'Map',
            'userId' => Session::getInstance()->read('id'),
            'mapWidth' => "col-9",
            'center' => [floatval($splitted[0]), floatval($splitted[1])],
            'hotspotInfo' => $this->buildHotspotInfo($hotspotId),
            'pressureList' => (new HotspotBroker())->getListOfPressures($hotspotId)
        ]);
    }

    public function renderWindsPage($hotspotId) {
        return $this->render('wind', [
            'hotspotId' => $hotspotId
        ]);
    }

    public function renderStore() {
        return $this->render('store');
    }

    public function renderFishPage($hotspotId) {
        return $this->render('fish', [
            'fishes' => (new FishBroker())->getAllFishFromHotspot($hotspotId)
        ]);
    }

    public function renderPermissionPage() {
        return $this->render('permissions', [
            'hotspots' => (new HotspotBroker())->getHotspotsInfoForPermissions(Session::getInstance()->read('id'))
        ]);
    }

    private function buildProjectTable($data) {
        return new TableObject([
            "Date",
            "Start Time",
            "End Time",
            "Pressure"], $data);
    }

    private function buildHotspotInfo($hotspotId) {
        $result = (new HotspotBroker())->getHotspotInfos($hotspotId);
        $hotspotInfo = (new HotspotBroker())->getHotspotsWindAvg($hotspotId);
        $hotspotInfo->id = $hotspotId;
        $hotspotInfo->catches = $this->calculateNbCatch($result);
        $hotspotInfo->nbHooks = $this->calculateNbHook($result);
        $hotspotInfo->nbBites = $this->calculateNbBites($result);
        return $hotspotInfo;
    }

    private function calculateNbCatch($result) {
        $total = 0;
        foreach ($result as $trip) {
            $total += $trip->catches;
        }
        return $total;
    }

    private function calculateNbHook($result) {
        $total = 0;
        foreach ($result as $trip) {
            $total += $trip->nbHooks;
        }
        return $total;
    }

    private function calculateNbBites($result) {
        $total = 0;
        foreach ($result as $trip) {
            $total += $trip->nbBites;
        }
        return $total;
    }
}