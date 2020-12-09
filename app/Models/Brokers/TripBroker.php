<?php namespace Models\Brokers;

class TripBroker extends Broker
{
    public function insert($userId): int
    {
        $sql = "insert into Trip values (default, ?, 0, 0, 0, current_time , null)";
        $this->query($sql, [$userId]);
        return $this->getDatabase()->getLastInsertedId();
    }

    public function update($tripId, $bites, $hooks, $dateEnd)
    {
        $sql = "update Trip set bites = ?, hooks = ?, dateTimeEnd = current_time where id = ?";
        $this->query($sql, [$bites, $hooks, $dateEnd, $tripId]);
    }
}