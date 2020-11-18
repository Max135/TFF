<?php namespace Controllers;

use Models\Brokers\CatchBroker;
use Models\Brokers\HotspotBroker;
use Models\Brokers\UserBroker;

class ApiController extends Controller
{

    public function initializeRoutes()
    {

        $this->post("/api/authenticate", "apiPostAuthenticate");
        $this->post("/api/catch", "apiPostCatch");
        $this->post("/api/catches", "apiPostCatches");
        $this->get('/api/hotspots', 'getUsersHotspots');
    }

    public function apiPostCatch()
    {
        $tripId = $this->getPostValue('tripId');
        $userId = $this->getPostValue('userId');
        $temperature = $this->getPostValue('temperature');
        $pressure = $this->getPostValue('pressure');
        $humidity = $this->getPostValue('humidity');
        $time = $this->getPostValue('time');
        $lng = $this->getPostValue('longitude');
        $lat = $this->getPostValue('latitude');

        $catchId = (new CatchBroker())->insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat);
        (new HotspotBroker())->createNewHotspot($catchId, $userId);
    }

    public function getUsersHotspots() {
        $userId = $_GET['userId'];
        $result = (new HotspotBroker())->getHotspots($userId);
        return $this->json($result);
    }

    public function apiPostCatches()
    {
        $catches = $_POST['catches'];
        foreach ($catches as $catch) {
            $catchId = (new CatchBroker())->insert($catch->tripId, $catch->temperature, $catch->pressure, $catch->humidity, $catch->time, $catch->lng, $catch->lat);
            (new HotspotBroker())->createNewHotspot($catchId, $catch->userId);
        }
    }

    public function apoPostAuthenticate()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $broker = new UserBroker();

            if ($broker->validCredentials($email, $password)) {
                return $this->json($broker->findById($broker->findId($email)));
            }
        }
        return $this->json('nil');
    }

    private function getPostValue(string $name)
    {
        if (isset($name)) {
            return $_POST[$name];
        }

        return null;
    }
}