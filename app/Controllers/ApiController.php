<?php namespace Controllers;

use Models\Brokers\UserBroker;

class ApiController extends Controller
{

    public function initializeRoutes()
    {
        $this->get("/api", "apiGet");
        $this->post("/api", "apiPost");
    }

    public function apiPost()
    {

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