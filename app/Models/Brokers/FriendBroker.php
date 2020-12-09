<?php namespace Models\Brokers;

class FriendBroker extends Broker
{
    public function getFriends($userId) {
        $sql = "Select F.userTwo as id, U.username From Friend F JOIN User U On F.userTwo = U.id WHERE userOne = ?";
        return $this->select($sql, [$userId]);
    }

    public function addFriend($userId, $email) {
        $userTwo = (new UserBroker())->findId($email);
        $sql = "Insert into Friend values (?, ?)";
        $this->query($sql, [$userId, $userTwo]);
        $this->query($sql, [$userTwo, $userId]);
    }

    public function removeFriend($userId, $friendId) {
        $sql = "DELETE FROM Friend WHERE userOne = ? and userTwo = ?";
        $this->query($sql, [$userId, $friendId]);
        $this->query($sql, [$friendId, $userId]);
    }
}