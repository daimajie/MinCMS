<?php
namespace app\models\setting;
use yii\data\ActiveDataProvider;

class SearchFriend extends Friend
{
    public function rules()
    {
        return [
            [['site'], 'string'],
        ];
    }


    public function search($params)
    {
        $query = Friend::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            //搜索不成功返回全部数据
            return $dataProvider;
        }

        if(!empty($this->name))
            $query->andFilterWhere(['like', 'name', $this->name]);


        //返回数据提供者
        return $dataProvider;
    }
}