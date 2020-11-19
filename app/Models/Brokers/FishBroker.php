<?php namespace Models\Brokers;

class FishBroker extends Broker
{
    public function insert($catchId, $species, $weight, $picturePath = null)
    {
        $sql = "insert into Fish values (default, ?, ?, ?, ?);";
        $this->query($sql, [$catchId, $species, $weight, $picturePath]);
    }

    public function getPicture($fishId)
    {
        $sql = "";
    }
}