<?php

namespace App\Http\Controllers;

use MongoDB\Client;

class ActivitiesController extends Controller
{
    protected $mongodb;

    protected $activities;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->mongodb = $client;
        $this->activities = $this->mongodb->tictac->activities;
    }

    public function index()
    {
        $activities = $this->activities->find();

        return $activities->toArray();
    }
}
