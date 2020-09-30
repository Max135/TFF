<?php namespace Controllers;

class LoginController extends \Zephyrus\Application\Controller
{

    /**
     * @inheritDoc
     */
    public function initializeRoutes()
    {
        $this->get("/index", "index");
    }

    public function index() {
        return $this->render("index", [
           'title'=>'Index'
        ]);
    }
}
