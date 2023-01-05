<?php

namespace app\models\search;

use app\models\StudentIdRequest;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * app\models\search\StudentIdRequestSearch represents the model behind the search form about `app\models\StudentIdRequest`.
 */
class StudentIdRequestSearch extends StudentIdRequest
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['request_id', 'request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'integer'],
            [['request_date', 'source'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = StudentIdRequest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['request_date' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'request_id' => $this->request_id,
            'request_type_id' => $this->request_type_id,
            'student_prog_curr_id' => $this->student_prog_curr_id,
            'request_date' => $this->request_date,
            'status_id' => $this->status_id,
            'receipt_number' => $this->receipt_number,
        ]);

        $query->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }
}
