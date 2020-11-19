<?php namespace Controllers;

use Models\Brokers\CatchBroker;
use Models\Brokers\HotspotBroker;
use Models\Brokers\TripBroker;
use Models\Brokers\UserBroker;
use Zephyrus\Network\Response;

class ApiController extends Controller
{

    public function initializeRoutes()
    {

        $this->post("/api/authenticate", "apiPostAuthenticate");
        $this->post("/api/catch", "apiPostCatch");
        $this->post("/api/catches", "apiPostCatches");
        $this->post('/api/trip', 'apiPostTrip');
        $this->get('/api/hotspots', 'getUsersHotspots');
    }

    /**
     * Parameters needed in post: tripId, temperature, pressure, humidity, time, longitude, latitude
     */
    public function apiPostCatch()
    {
        $tripId = $this->getPostValue('tripId');
        $temperature = $this->getPostValue('temperature');
        $pressure = $this->getPostValue('pressure');
        $humidity = $this->getPostValue('humidity');
        $time = $this->getPostValue('time');
        $lng = $this->getPostValue('longitude');
        $lat = $this->getPostValue('latitude');

        (new CatchBroker())->insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat);
        $this->savePicture();
//        (new HotspotBroker())->createNewHotspot($catchId, $userId);
    }

    /**
     * Saves the picture in the server files and insert the path of the file in database
     */
    public function savePicture()
    {
        $targetDir = "assets/images/" . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $targetDir);
    }

    /**
     * insert a new trip into database and return it's id
     *
     * @return Response
     */
    public function apiPostTrip()
    {
        $userId = $this->getPostValue('userId');
        $bites = $this->getPostValue('bites');
        $hooks = $this->getPostValue('hooks');
        $dateStart = $this->getPostValue('dateStart');
        $dateEnd = $this->getPostValue('dateEnd');

        $tripId = (new TripBroker())->insert($userId, $bites, $hooks, $dateStart, $dateEnd);

        return $this->json($tripId);
    }

    public function getUsersHotspots() {
        $userId = $_GET['userId'];
        $result = (new HotspotBroker())->getHotspots($userId);
        return $this->json($result);
    }

    /**
     * To insert an array of catches
     */
    public function apiPostCatches()
    {
        $catches = $_POST['catches'];
        foreach ($catches as $catch) {
            (new CatchBroker())->insert($catch->tripId, $catch->temperature, $catch->pressure, $catch->humidity, $catch->time, $catch->lng, $catch->lat);
        }
    }

    /**
     * Verify the crendentials, returns nil if bad, or the user's data if correct
     *
     * @return Response
     */
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

    /**
     * @param string $name
     * @return mixed|null
     */
    private function getPostValue(string $name)
    {
        if (isset($name)) {
            return $_POST[$name];
        }

        return null;
    }
}