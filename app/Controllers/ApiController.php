<?php namespace Controllers;

use Models\Brokers\ApiLogsBroker;
use Models\Brokers\CatchBroker;
use Models\Brokers\FishBroker;
use Models\Brokers\HotspotBroker;
use Models\Brokers\TripBroker;
use Models\Brokers\UserBroker;
use stdClass;
use Zephyrus\Network\Response;

class ApiController extends Controller
{

    public function initializeRoutes()
    {
        $this->post("/api/authenticate", "apiPostAuthenticate");
        $this->post("/api/catch", "apiPostCatch");
        $this->post("/api/catches", "apiPostCatches");
        $this->post('/api/trip', 'apiPostTrip');
        $this->post('/api/image', 'savePicture');
        $this->get('/api/image', 'savePicture');
        $this->get('/api/hotspots', 'getUsersHotspots');
        $this->get('/api/hotspotWinds', 'getHotspotWinds');

        $this->get("/api/logs", 'showLogs');
        $this->get("/api/logs/successful", "showSuccessfulLogs");
        $this->get("/api/logs/unsuccessful", "showUnSuccessfulLogs");
        $this->get("/api/logs/clear", "clearLogs");
        $this->get("/api/changePerm", "switchPerm");
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

        $species = $this->getPostValue('species');
        $weight = $this->getPostValue('weight');

        $catchId = (new CatchBroker())->insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat);
        $pictureName = $this->savePicture();
        (new FishBroker())->insert($catchId, $species, $weight, $pictureName);

        return $this->json($this->makeCatchObject($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat, $species, $weight, $catchId, $pictureName));
    }

    /**
     * Meant for api tests
     *
     * @param $tripId
     * @param $temp
     * @param $pres
     * @param $hum
     * @param $time
     * @param $lng
     * @param $lat
     * @param $species
     * @param $weight
     * @param $id
     * @param $pic
     * @return stdClass
     */
    private function makeCatchObject($tripId, $temp, $pres, $hum, $time, $lng, $lat, $species, $weight, $id, $pic) {
        $obj = new stdClass();
        $obj->tripId = $tripId;
        $obj->temperature = $temp;
        $obj->pressure = $pres;
        $obj->humidity = $hum;
        $obj->time = $time;
        $obj->lng = $lng;
        $obj->lat = $lat;
        $obj->species = $species;
        $obj->weight = $weight;
        $obj->catchId = $id;
        $obj->picture = $pic;

        return $obj;
    }

    /**
     * Saves the picture in the server files
     */
    public function savePicture()
    {
        $pictureName = time() . str_replace(" ", "_", basename($_FILES['file']['name']));
        $targetDir = "assets/images/" . $pictureName;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir)) {
            return $pictureName;
        }
        return null;
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
        $throws = $this->getPostValue('throws');
        $dateStart = $this->getPostValue('dateStart');
        $dateEnd = $this->getPostValue('dateEnd');

        $tripId = (new TripBroker())->insert($userId, $bites, $hooks, $throws, $dateStart, $dateEnd);

        return $this->json($tripId);
    }

    /**
     * Function to update the trip at the end of it
     */
    public function updatePostTrip()
    {
        $id = $this->getPostValue('tripId');
        $bites = $this->getPostValue('bites');
        $hooks = $this->getPostValue('hooks');
        $dateEnd = $this->getPostValue('dateEnd');

        (new TripBroker())->update($id, $bites, $hooks, $dateEnd);
    }

    /**
     * (For map.pug AJAX) Returns all the users hotspots
     *
     * @return Response
     */
    public function getUsersHotspots() {
        $userId = $_GET['userId'];
        $result = (new HotspotBroker())->getHotspots($userId);
        return $this->json($result);
    }

    /**
     * (For winds.pug AJAX) Returns the avg array and the range array of winds for all of the trips inside the hotspot
     *
     */
    public function getHotspotWinds() {
        $hotspotId = $_GET['hotspotId'];
        $result = [];
        $result[0] = (new HotspotBroker())->getHotspotsWindsRange($hotspotId);
        $result[1] = (new HotspotBroker())->getHotspotsWindsAvgByTrip($hotspotId);
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
     * Verify the credentials, returns blank user with id = 0 if bad, or the user's data if correct
     *
     * @return Response
     */
    public function apiPostAuthenticate()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            (new ApiLogsBroker())->insert(true, "Credentials are set ; " . $_POST['email'] . " : " . $_POST['password']);
            $email = $_POST['email'];
            $password = $_POST['password'];
            $broker = new UserBroker();

            if ($broker->validCredentials($email, $password)) {
                return $this->json($broker->findById($broker->findId($email)));
            }
        }

        (new ApiLogsBroker())->insert(false, "Credentials not found / not valid");
        return $this->json($this->createErrorUser());
    }

    /**
     *  Prints all data from ApiLogs
     */
    public function showLogs()
    {
        return $this->json((new ApiLogsBroker())->findAll());
    }

    /**
     *  Prints all logs that are successful
     *
     * @return Response
     */
    public function showSuccessfulLogs()
    {
        return $this->json((new ApiLogsBroker())->findAllBySuccess(true));
    }

    /**
     *  Prints all logs that are unsuccessful
     *
     * @return Response
     */
    public function showUnSuccessfulLogs()
    {
        return $this->json((new ApiLogsBroker())->findAllBySuccess(false));
    }

    /**
     *  Delete all from the ApiLogs Table
     *
     * @return Response
     */
    public function clearLogs()
    {
        (new ApiLogsBroker())->deleteAll();
        return $this->redirect("/api/logs");
    }

    /**
     * Create a mock user to respond to authenticate route
     *
     * @return stdClass
     */
    private function createErrorUser()
    {
        $user = new stdClass();
        $user->id = 0;
        $user->email = "";
        $user->password = "";
        $user->username = "";
        $user->picturePath = "";
        return $user;
    }

    /**
     * Route to switch the permission of a hotspot (if it gets shared or not)
     *
     */
    public function switchPerm() {
        $hotspotId = $_GET['hotspotId'];
        (new HotspotBroker())->changePerm($hotspotId);
        return $this->json(1);
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