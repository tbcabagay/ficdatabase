<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Storage;

/**
 * StorageSearch represents the model behind the search form about `app\models\Storage`.
 */
class StorageSearch extends Storage
{
    public $reference_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'notice_id', 'course_id', 'status'], 'integer'],
            [['location', 'created_at', 'reference_number'], 'safe'],
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
    public function search($faculty_id, $params)
    {
        $query = Storage::find()
            ->joinWith('notice')
            ->where(['notice.faculty_id' => $faculty_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['reference_number'] = [
            'asc' => ['notice.reference_number' => SORT_ASC],
            'desc' => ['notice.reference_number' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'notice_id' => $this->notice_id,
            'course_id' => $this->course_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'notice.reference_number', $this->reference_number]);

        return $dataProvider;
    }
}
