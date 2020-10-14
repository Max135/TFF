<?php namespace Controllers;


class ApiController extends Controller
{

    public function initializeRoutes()
    {
        $this->get("/api", "api");
    }
}