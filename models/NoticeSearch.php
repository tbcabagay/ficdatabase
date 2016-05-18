<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Notice;

/**
 * NoticeSearch represents the model behind the search form about `app\models\Notice`.
 */
class NoticeSearch extends Notice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'faculty_id', 'template_id', 'course_id'], 'integer'],
            [['reference_number', 'semester', 'academic_year', 'date_course_start', 'date_final_exam', 'date_submission', 'created_at'], 'safe'],
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
        $query = Notice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'faculty_id' => $this->faculty_id,
            'template_id' => $this->template_id,
            'course_id' => $this->course_id,
            'date_course_start' => $this->date_course_start,
            'date_final_exam' => $this->date_final_exam,
            'date_submission' => $this->date_submission,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year]);

        return $dataProvider;
    }
}
