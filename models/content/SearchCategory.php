<?php
namespace app\models\content;
use yii\data\ActiveDataProvider;

class SearchCategory extends Category
{
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }


    public function search($params)
    {
        $query = Category::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            //搜索不成功返回全部数据
            return $dataProvider;
        }
        $query->andFilterWhere([
            'name' => $this->name,
        ]);
        /*$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);*/

        //返回数据提供者
        return $dataProvider;
    }
}