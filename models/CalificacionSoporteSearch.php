<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CalificacionSoporte;

/**
 * CalificacionSoporteSearch represents the model behind the search form of `app\models\CalificacionSoporte`.
 */
class CalificacionSoporteSearch extends CalificacionSoporte
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'p1', 'p2', 'p3', 'p4', 'p5', 'id_operador'], 'integer'],
            [['numero_serie'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = CalificacionSoporte::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'p1' => $this->p1,
            'p2' => $this->p2,
            'p3' => $this->p3,
            'p4' => $this->p4,
            'p5' => $this->p5,
            'id_operador' => $this->id_operador,
        ]);

        $query->andFilterWhere(['like', 'numero_serie', $this->numero_serie]);

        return $dataProvider;
    }
}
