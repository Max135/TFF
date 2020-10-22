<?php namespace Models\Brokers;

use Zephyrus\Security\Cryptography;

class UserBroker extends Broker
{
    public function insert(string $email, string $password, string $username): int
    {
        $sql = "insert into User values (default, ?, ?, ?, null);";
        $this->query($sql, [$email, Cryptography::hashPassword($password), $username]);

        return $this->getDatabase()->getLastInsertedId();
    }

    public function findById(int $id)
    {
        $sql = "select * from User where id = ?;";
        return $this->selectSingle($sql, [$id]);
    }

    public function findId(string $email): ?int
    {
        $sql = "select id from User where email = ?;";

        $result = $this->selectSingle($sql, [$email]);
        return (is_null($result)) ? null : $result->id;
    }

    public function validCredentials(string $email, string $password): bool
    {
        $sql = "select password from User where email = ?";
        $hashedPassword = $this->selectSingle($sql, [$email]);
        if ($hashedPassword != null) {
            return Cryptography::verifyHashedPassword($password, $hashedPassword);
        }
        return false;
    }
}