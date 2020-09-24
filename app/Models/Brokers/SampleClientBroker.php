<?php namespace Models\Brokers;

use stdClass;

class SampleClientBroker extends Broker
{
    public function findAll()
    {
        return $this->select("SELECT * FROM client");
    }

    public function findById($clientId)
    {
        return $this->selectSingle("SELECT * FROM client WHERE client_id = ?", [$clientId]);
    }

    public function insert(stdClass $client): int
    {
        $this->query("INSERT INTO client(first_name, last_name, email) VALUES (?, ?, ?)", [
            $client->first_name,
            $client->last_name,
            $client->email
        ]);
        return $this->getDatabase()->getLastInsertedId();
    }
}
