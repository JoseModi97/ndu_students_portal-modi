<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use Exception;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model
{
    public string $username = '';
    public string $password = '';

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @throws Exception
     */
    public function validatePassword(string $attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            try{
                if (!$user || !$user->validatePassword($this->password)) {
                    $this->addError($attribute, 'Incorrect username or password.');
                }
            }catch (Exception $ex){
                throw new Exception($ex->getMessage());
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return array|bool|ActiveRecord
     */
    public function getUser(): array|bool|User
    {
        return User::findByUsername($this->username);
    }
}
