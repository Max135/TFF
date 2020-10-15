<?php namespace Models\Brokers;

class UserBroker extends Broker
{
    public function insert(string $email, string $password, string $username): int
    {
        $sql = "insert into User values (default, ?, ?, ?, null);";
        $this->query($sql, [$email, $password, $username]);

        return $this->getDatabase()->getLastInsertedId();
    }

    public function findById(int $id)
    {
        $sql = "select username, password from User where id = ?;";
        return $this->selectSingle($sql, [$id]);
    }
}