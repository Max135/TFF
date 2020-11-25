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
        $sql = "select picturePath from Fish where id = ?;";
        return $this->selectSingle($sql, [$fishId])->picturePath;
    }

    public function getAllFishesFromHotspot($hotspotId) {
        $sql = "Select C.time, F.species, F.weight, F.picturePath From Catch C Join Fish F on C.id = F.catchId Where hotspotId = 3";
        return $this->select($sql, [$hotspotId]);
    }
}