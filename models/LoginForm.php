<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\base\Model;

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
            [['username', 'password'], 'required'],
            [['username', 'password'], 'trim'],
            [
                'username',
                'match',
                'pattern' => '/^[a-zA-Z0-9]+\/[a-zA-Z0-9]+\/[0-9]{4}$/',
                'message' => 'Registration must be correct and in the format XX/0000/2022.',
            ],
        ];
    }
}
