<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models\search;

use app\models\NameChange;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NameChangeRequests extends NameChange
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @param array $moreParams
     * @return ActiveDataProvider
     */
    public function search(array $params, array $moreParams = []): ActiveDataProvider
    {
        $studentId = $moreParams['studentId'];

        $query = NameChange::find()->alias('nc')
            ->select([
                'nc.name_change_id',
                'nc.new_surname',
                'nc.new_othernames',
                'nc.current_surname',
                'nc.current_othernames',
                'nc.reason',
                'nc.document_url',
                'nc.status',
                'nc.request_date'
            ])
            ->where(['nc.student_id' => $studentId])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->orderBy(['nc.name_change_id' => SORT_DESC]);

        return $dataProvider;
    }
}