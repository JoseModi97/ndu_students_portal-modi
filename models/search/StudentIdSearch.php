<?php

namespace app\models\search;

use app\models\extended\StudentProgramme;
use app\models\StudentId;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * app\models\search\StudentIdSearch represents the model behind the search form about `app\models\StudentId`.
 */
class StudentIdSearch extends StudentId
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id_serial_no', 'student_prog_curr_id', 'barcode'], 'integer'],
            [['issuance_date', 'valid_from', 'valid_to', 'id_status'], 'safe'],
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
        $query = StudentId::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_status' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'student_id_serial_no' => $this->student_id_serial_no,
            'student_prog_curr_id' => $this->student_prog_curr_id,
            'issuance_date' => $this->issuance_date,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'barcode' => $this->barcode,
            'id_status' => $this->id_status,
        ]);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @param int $recordLimit
     * @return ActiveDataProvider
     */
    public function activeStudentRecord(array $params, int $recordLimit = 2): ActiveDataProvider
    {
        $query = StudentId::find()
            ->where(['in', 'student_prog_curr_id', StudentProgramme::loadStudentProgrammes()]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => ['defaultOrder' => ['id_status' => SORT_ASC]],
//            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'student_id_serial_no' => $this->student_id_serial_no,
            'issuance_date' => $this->issuance_date,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'barcode' => $this->barcode,
            'id_status' => $this->id_status,
        ]);

        return $dataProvider;
    }
}
