<?php

namespace app\models\content;

use app\models\member\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property int $id 主键
 * @property string $name 标签名
 * @property int $topic_id 所属话题
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['topic_id','name'], 'required'],

            [['topic_id'], 'exist', 'targetClass' => 'app\models\content\Topic', 'targetAttribute' => 'id'],
            [['name'], 'string', 'max' => 12],

            //检测该话题下包含的标签是否达到上限
            [['topic_id'], 'checkUpperLimit'],

            //每个话题下标签确保唯一性
            [['name'], 'checkUnique'],
        ];
    }

    /**
     * 每个话题内的标签必须是唯一的
     */
    public function checkUnique($attr){
        if(!$this->hasErrors()){
            //检测是否存在
            $exist = static::find()
                ->where(['name' => $this->$attr])
                ->andWhere(['topic_id' => $this->topic_id])
                ->count();
            if($exist > 0)
                $this->addError($attr, '所选话题下已经存在该标签。');
        }
    }

    /**
     * 检测制定话题标签是否达到上限
     */
    public function checkUpperLimit($attr){
        if (!$this->hasErrors()){
            //获取参数
            $upperLimt = Yii::$app->params['tagUpperLimit'];

            //获取该话题下标签数量
            $count = Topic::find()->where(['id'=>$this->$attr])->count();

            if($count >= $upperLimt){
                //已经达到上限
                $this->addError($attr, '该话题下标签数量已达上限。');
            }
        }
    }

    //自动完成 创建者 和时间戳
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '标签名',
            'topic_id' => '所属话题',
            'user_id' => '创建者'
        ];
    }

    /**
     * 关联话题
     * @return \yii\db\ActiveQuery
     */
    public function getTopic(){
        return $this->hasOne(Topic::className(), ['id' => 'topic_id'])
            ->select(['id', 'name']);
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id'])
            ->select(['id','username']);
    }

    /**
     * 根据提供的话题id获取其下所有标签数据
     * @param $topic_id int #话题id
     * @return array #话题下所有标签，如果没有返回孔氏族
     */
    public static function getTagsByTopic($topic_id){
        $data = static::find()
            ->select(['id','name'])
            ->where(['topic_id'=>$topic_id])
            ->asArray()
            ->all();
        $tmp = [];
        foreach ($data as $id => $name){
            $tmp[$name['id']] = $name['name'];
        }
        return $tmp;
    }




}
