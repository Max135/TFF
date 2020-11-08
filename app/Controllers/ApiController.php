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
        $tripId = $this->getPostValue('tripId');
        $temperature = $this->getPostValue('temperature');
        $pressure = $this->getPostValue('pressure');
        $humidity = $this->getPostValue('humidity');
        $time = $this->getPostValue('time');
        $lng = $this->getPostValue('longitude');
        $lat = $this->getPostValue('latitude');
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

    private function getPostValue(string $name)
    {
        if(isset($name)) {
            return $_POST[$name];
        }

        return null;
    }
}