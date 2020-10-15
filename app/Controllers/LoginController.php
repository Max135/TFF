<?php namespace Controllers;

class LoginController extends \Zephyrus\Application\Controller
{

    /**
     * @inheritDoc
     */
    public function initializeRoutes()
    {
        $this->get("/", "login");
        $this->get("/index", "index");
        $this->get("/login", "login");
        $this->post("/login", "loginUser");
        $this->get("/signup", "signup");
    }

    public function index() {
        return $this->render("index", [
           'title'=>'Index'
        ]);
    }

    public function login() {
        return $this->render("login", [
            'title' => 'Login'
        ]);
    }

    public function loginUser() {

    }

    public function signup() {
        return $this->render("signup", [
            'title' => 'Signup'
        ]);
    }
}
