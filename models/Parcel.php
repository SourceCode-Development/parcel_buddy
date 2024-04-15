<?php

namespace app\models;

use app\core\db\DbModel;
use app\core\Application;

class Parcel extends DbModel
{
    public int $id = 0;
    public string $recipient_name = '';
    public string $delivery_address = '';
    public string $latitude = '';
    public string $longitude = '';
    public string $postcode = '';
    public string $img_path = '';
    public int $assigned_to = 0;
    public int $status = 1;

    public static function tableName(): string
    {
        return 'parcels';
    }

    public function attributes(): array
    {
        return [
        'id',
        'recipient_name',
        'delivery_address',
        'latitude',
        'longitude',
        'postcode',
        'img_path',
        'assigned_to',
        'status'
        ];
    }

    public function labels(): array
    {
        return [
            'recipient_name' => 'Recipient Name',
            'delivery_address' => 'Full address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'postcode' => 'Postal Code',
            'img_path' => 'Image',
            'assigned_to' => 'Assign To',
            'status' => 'Status',
        ];
    }

    public function rules()
    {
        return [
            'recipient_name' => [self::RULE_REQUIRED],
            'delivery_address' => [self::RULE_REQUIRED],
            'latitude' => [self::RULE_REQUIRED],
            'longitude' => [self::RULE_REQUIRED],
            'postcode' => [self::RULE_REQUIRED],
            // 'status' => [self::RULE_REQUIRED],
            'assigned_to' => [self::RULE_REQUIRED],
        ];
    }

    public static function get_parcel_count(){
        $statement = Application::$app->db->prepare("SELECT count(p.id) as parcel_count FROM parcels p inner join users u on p.assigned_to = u.id ");

        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($result)){
            return 0;
        }

        return $result[0]['parcel_count'];
    }

    public static function get_parcels($limit = 5, $offset = 0){
        $statement = Application::$app->db->prepare("SELECT p.id, p.recipient_name, p.delivery_address, p.latitude, p.longitude,p.postcode,ps.status_title , u.name FROM parcels p inner join users u on p.assigned_to = u.id inner join parcel_status ps on p.status = ps.status_id limit :lim offset :offs ");
        $statement->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offs', $offset, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function get_parcel_count_for_user($user_id){
        $statement = Application::$app->db->prepare("SELECT count(p.id) as parcel_count FROM parcels p inner join users u on p.assigned_to = u.id where u.id = :user_id group by u.id ");
        $statement->bindValue(':user_id', $user_id);

        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($result)){
            return 0;
        }

        return $result[0]['parcel_count'];
    }

    public static function get_parcel_for_user($user_id, $limit = 5, $offset = 0){
        $statement = Application::$app->db->prepare("SELECT p.id, p.recipient_name, p.delivery_address, p.latitude, p.longitude,p.postcode,ps.status_title , u.name FROM parcels p inner join users u on p.assigned_to = u.id inner join parcel_status ps on p.status = ps.status_id where u.id = :user_id limit :lim offset :offs ");
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offs', $offset, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function save()
    {
        if(!parent::save()){
            return false;
        }

        $this->id = Application::$app->db->pdo->lastInsertId();
        return true;
    }

    public function update(){
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $query = "UPDATE $tableName SET ";
        $length = \count($attributes);
        $count = 1;
        foreach($attributes as $param){
            if($param == 'id'){
                $count++;
                continue;
            }
            if($length == $count){
                $query = $query . $param . '= :' . $param . ' ';
            }
            else{
                $query = $query . $param . '= :' . $param . ',';
            }
            $count++;
        }

        $query = $query . " where id = :id";

        $statement = self::prepare($query);
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $status = $statement->execute();

        if(!$status){
            return false;
        }
        return true;
    }

    public static function get_parcel($parcel_id){
        $statement = Application::$app->db->prepare("SELECT p.id, p.recipient_name, p.delivery_address, p.latitude, p.longitude, p.postcode, p.assigned_to, ps.status_title FROM parcels p inner join parcel_status ps on p.status = ps.status_id where p.id = :parcel_id;");
        $statement->bindValue(':parcel_id', $parcel_id);

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}