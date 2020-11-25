<?php namespace Models\Brokers;

class TripBroker extends Broker
{
    public function insert($userId, $bites, $hooks, $dateStart, $dateEnd): int
    {
        $sql = "insert into Trip values (default, ?, ?, ?, ?, ?, ?)";
        $this->query($sql, [$userId, $bites, $hooks, $dateStart, $dateEnd]);
        return $this->getDatabase()->getLastInsertedId();
    }

    public function update($tripId, $bites, $hooks, $dateEnd)
    {
        $sql = "update Trip set bites = ?, hooks = ?, dateTimeEnd = ? where id = ?";
        $this->query($sql, [$bites, $hooks, $dateEnd, $tripId]);
    }
}