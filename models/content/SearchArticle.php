<?php
namespace app\models\content;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class SearchArticle extends Article
{
    public function rules()
    {
        return [
            [['topic_id','recommend','checked','type'], 'integer'],
        ];
    }


    public function search($params)
    {
        $query = Article::find();//->with('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            //搜索不成功返回全部数据
            return $dataProvider;
        }

        if(!empty($this->topic_id))
            $query->andFilterWhere(['topic_id' => $this->topic_id]);
        if(!empty($this->recommend))
            $query->andFilterWhere(['recommend' => $this->recommend]);
        if(!empty($this->checked))
            $query->andFilterWhere(['checked' => $this->checked]);
        if(!empty($this->type))
            $query->andFilterWhere(['topic_id' => $this->type]);

        /*$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);*/

        //返回数据提供者
        return $dataProvider;
    }
}