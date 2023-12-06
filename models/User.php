<?php

namespace app\models;

use app\core\UserModel;
use app\core\Application;

class User extends UserModel
{
    public int $id = 0;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public int $status = 1;
    public int $role_id = 1;

    public static function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['id','name','email','password','status','role_id'];
    }

    public function labels(): array
    {
        return [
            'name' => 'User name',
            'email' => 'Email',
            'password' => 'Password',
            'passwordConfirm' => 'Password Confirm',
            'role_id' => 'Select Role',
        ];
    }

    public function rules()
    {
        return [
            'name' => [self::RULE_REQUIRED, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],
            'role_id' => [self::RULE_REQUIRED],
        ];
    }

    public function validate_for_update(){
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorByRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $rule['min']]);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorByRule($attribute, self::RULE_MAX);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $db = Application::$app->db;
                    $statement = $db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :$uniqueAttr and id != :id");
                    $statement->bindValue(":$uniqueAttr", $value);
                    $statement->bindValue(":id", $this->id);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorByRule($attribute, self::RULE_UNIQUE);
                    }
                }
            }
        }
        return empty($this->errors);
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
            if($attribute == 'password'){
                $this->{$attribute} = \password_hash($this->{$attribute}, PASSWORD_DEFAULT);
            }
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $status = $statement->execute();

        if(!$status){
            return false;
        }
        return true;
    }

    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        return parent::save();
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }
}