<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Device;

/**
 * DevicesSearch represents the model behind the search form of `app\models\Devices`.
 */
class DeviceSearch extends Device
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sshport', 'active'], 'integer'],
            [['name', 'ip_address', 'username', 'password', 'laststatus'], 'safe'],
            [['lastok', 'lastbad'], 'datetime']
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Device::find();

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
            'sshport' => $this->sshport,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'laststatus', $this->laststatus]);

        return $dataProvider;
    }
}
