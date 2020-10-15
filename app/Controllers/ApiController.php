<?php namespace Controllers;

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

    }
}