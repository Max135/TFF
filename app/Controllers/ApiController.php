<?php namespace Controllers;

use Models\Brokers\ApiLogsBroker;
use Models\Brokers\CatchBroker;
use Models\Brokers\FishBroker;
use Models\Brokers\HotspotBroker;
use Models\Brokers\TripBroker;
use Models\Brokers\UserBroker;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
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
        $this->post('/api/end-trip', 'updatePostTrip');

        $this->post('/api/image', 'savePicture');
        $this->get('/api/image', 'savePicture');
        $this->get('/api/hotspots', 'getUsersHotspots');
        $this->get('/api/friendsHotspots', 'getFriendsHotspots');
        $this->get('/api/hotspotWinds', 'getHotspotWinds');

        $this->get("/api/logs", 'showLogs');
        $this->get("/api/logs/successful", "showSuccessfulLogs");
        $this->get("/api/logs/unsuccessful", "showUnSuccessfulLogs");
        $this->get("/api/logs/clear", "clearLogs");
        $this->get("/api/changePerm", "switchPerm");
        $this->post("/api/fetch-wind-data", "getWindValue");
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
        $wind = $this->getPostValue('wind');
        $time = $this->getPostValue('time');
        $lng = $this->getPostValue('longitude');
        $lat = $this->getPostValue('latitude');

        $species = $this->getPostValue('species');
        $weight = $this->getPostValue('weight');

        $catchId = (new CatchBroker())->insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat);
        (new CatchBroker())->insertWind($catchId, $wind);
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

        $tripId = (new TripBroker())->insert($userId);

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
//        $dateEnd = $this->getPostValue('dateEnd');

        (new TripBroker())->update($id, $bites, $hooks);
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

    public function getFriendsHotspots() {
        $userId = $_GET['userId'];
        $result = (new HotspotBroker())->getSharedHotspots($userId);
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
     * [POST: /api/fetch-wind-data]
     * Responds with the wind value (speed/direction) from the specified lat and lon params
     *
     * @return Response
     */
    public function getWindValue()
    {
        if (isset($_POST['lat']) && isset($_POST['lon'])) {
            $lat = $this->getPostValue('lat');
            $lon = $this->getPostValue('lon');
            $result = $this->callWindApi($lat, $lon);
            $wind = $this->generateWindResponse($result);
            return $this->json($wind);
        } else {
            return $this->json($this->generateErrorWind());
        }
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
        return $this->json("1");
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

    /**
     * Calls the wind API and returns it's result in an array of associative arrays
     * https://stackoverflow.com/a/9802854
     *
     * api-key : crHg9RN912tS97pM7pLldK40bvWEqUdS
     * route example : https://api.climacell.co/v3/weather/realtime?lat=46.0428&lon=-73.1123&unit_system=si&fields=wind_direction%2Cwind_speed&apikey=crHg9RN912tS97pM7pLldK40bvWEqUdS
     *
     * @param $lat
     * @param $lon
     * @return mixed
     */
    private function callWindApi($lat, $lon)
    {
        $baseUrl = "https://api.climacell.co/v3/weather/realtime";
        $apikey = "crHg9RN912tS97pM7pLldK40bvWEqUdS";
        $unit_system = "si";
        $fields = ["wind_speed", "wind_direction"];
        $data = [
            'lat' => $lat,
            'lon' => $lon,
            'unit_system' => $unit_system,
            'fields' => $fields,
            'apikey' => $apikey
        ];

        $url = sprintf("%s?%s", $baseUrl, http_build_query($data));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true);
    }

    /**
     * Generates and format the wind object to send as response
     *
     * @param $element
     * @return stdClass
     */
    private function generateWindResponse($element)
    {
        $wind = new stdClass();
//        $windSpeed = $element['wind_speed']['value'] // gets the original wind speed value;
//        $windUnit = $element['wind_speed']['units']; // gets the original wind speed unit
        $windSpeed = $this->convertWindSpeedUnit(floatval($element['wind_speed']['value']));
        $windUnit = 'km/h';
        $windDegree = floatval($element['wind_direction']['value']);
        $windDirection = $this->getWindDirection($windDegree);
        $wind->value = $windDirection . " " . $windSpeed . " " . $windUnit;
        return $wind;
    }

    /**
     * Converts the original wind speed value from m/s to km/h
     *
     * @param $mpsSpeed
     * @return string
     */
    private function convertWindSpeedUnit($mpsSpeed)
    {
        $kphSpeed = $mpsSpeed / 0.2777778;
        return number_format($kphSpeed, 1);
    }

    /**
     * Determines the wind direction acronym from it's degree (out of 360)
     * https://gist.github.com/smallindine/d227743c28418f3426ed36b8969ded1a#gistcomment-2973520
     * @param $deg
     * @return string
     */
    private function getWindDirection($deg)
    {
        $directions = array('N','NNE','NE','ENE','E','ESE','SE','SSE','S','SSO','SO','OSO','O','ONO','NO','NNO','N2');
        $cardinal = $directions[round($deg / 22.5)];
        if($cardinal == 'N2') {
            $cardinal = 'N';
        }

        return $cardinal;
    }

    /**
     * Generates an "error" wind response
     *
     * @return stdClass
     */
    private function generateErrorWind()
    {
        $errorWind = new stdClass();
        $errorWind->value = "ERROR";
        return $errorWind;
    }
}