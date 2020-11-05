<?php namespace Controllers;

use Models\Brokers\CatchBroker;
use Models\Brokers\UserBroker;

class ApiController extends Controller
{

    public function initializeRoutes()
    {
        $this->get("/api", "apiGet");

        $this->post("/api", "apiPost");
        $this->post("/api/catch", "apiPostCatch");
    }

    public function apiPost()
    {

    }

    public function apiPostCatch()
    {
        $tripId = $_POST['tripId'];
        $temperature = $_POST['temperature'];
        $pressure = $_POST['pressure'];
        $humidity = $_POST['humidity'];
        $time = $_POST['time'];
        $lng = $_POST['longitude'];
        $lat = $_POST['latitude'];
        (new CatchBroker())->insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat);
    }

    public function apiGet()
    {
        if (isset($_GET['email']) && isset($_GET['password'])) {
            $email = $_GET['email'];
            $password = $_GET['password'];
            $broker = new UserBroker();

            if ($broker->validCredentials($email, $password)) {
                return $this->json($broker->findById($broker->findId($email)));
            }
        }
        return $this->json('nil');
    }
}