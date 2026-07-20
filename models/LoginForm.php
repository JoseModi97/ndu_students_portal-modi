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
        ];
    }
}
