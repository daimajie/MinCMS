<?php
namespace app\models\content;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class SearchTag extends Tag
{
    public function rules()
    {
        return [
            [['topic_id'], 'integer'],
        ];
    }


    public function search($params)
    {
        $query = Tag::find()->with(['topic','user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            //搜索不成功返回全部数据
            return $dataProvider;
        }

        if(!empty($this->topic_id) && $this->topic_id > 0)
            $query->andFilterWhere(['topic_id' => $this->topic_id]);


        /*$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);*/

        //返回数据提供者
        return $dataProvider;
    }
}