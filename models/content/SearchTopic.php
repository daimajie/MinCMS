<?php
namespace app\models\content;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use Yii;

class SearchTopic extends Topic
{
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['category_id'], 'integer'],
        ];
    }


    public function search($params)
    {
        $query = Topic::find()->with('category');

        if(Yii::$app->user->identity->group < 2){
            //如果不是管理员的就显示自己的文章
            $query->where(['user_id'=>Yii::$app->user->id]);
        }


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

        if(!empty($this->category_id) && $this->category_id > 0)
            $query->andFilterWhere(['category_id' => $this->category_id]);


        /*$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);*/

        //返回数据提供者
        return $dataProvider;
    }
}