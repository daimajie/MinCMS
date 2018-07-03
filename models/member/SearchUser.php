<?php
namespace app\models\member;
use yii\data\ActiveDataProvider;

class SearchUser extends User
{
    public function rules()
    {
        return [
            [['group'], 'in', 'range' => [0,1,2], 'message'=>'请选择正确群组数据'],
        ];
    }


    public function search($params, $type='index')
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);




        $this->load($params);
        if (!$this->validate()) {
            //搜索不成功返回全部数据
            return $dataProvider;
        }

        //按话题搜索
        if(!empty($this->role_id))
            $query->andFilterWhere(['group' => $this->role_id]);



        //返回数据提供者
        return $dataProvider;
    }

}