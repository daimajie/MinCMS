<?php
namespace app\models\content;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;

class SearchArticle extends Article
{
    public function rules()
    {
        return [
            [['topic_id','checked','type'], 'integer'],
        ];
    }


    public function search($params, $type='index')
    {
        $query = Article::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        //判断获取数据的类型（回收站 草稿箱 文章首页（正常文章））
        $this->setDataType($query, $type);



        $this->load($params);
        if (!$this->validate()) {
            //搜索不成功返回全部数据
            return $dataProvider;
        }

        //按话题搜索
        if(!empty($this->topic_id))
            $query->andFilterWhere(['topic_id' => $this->topic_id]);

        //按状态搜索
        if(!empty($this->checked))
            $query->andFilterWhere(['checked' => $this->checked]);

        //按类型搜索
        if(!empty($this->type))
            $query->andFilterWhere(['topic_id' => $this->type]);


        //返回数据提供者
        return $dataProvider;
    }

    /**
     * 获取指定类型的文章数据列表
     * @param ActiveQuery $query object #等待添加筛选条件的aq对象
     * @param $type string #类型
     */
    private function setDataType(ActiveQuery $query, $type){
        if(!in_array($type, ['draft', 'recycle', 'index'])){
            $type = 'index';
        }
        switch ($type){
            case 'draft': //草稿箱 排除以删除的文章
                $query->andWhere(['draft'=>1]);
                $query->andWhere(['recycle'=>0]);
                break;
            case 'recycle'://回收站 所有以删除的文章
                $query->andWhere(['recycle'=>1]);
                break;
            default: //index 文章列表 排除草稿箱 回收站的文章
                $query->andWhere(['draft'=>0]);
                $query->andWhere(['recycle'=>0]);
                break;
        }
    }
}