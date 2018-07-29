<?php
namespace app\models\rbac;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;

class SearchAuthItem extends Model
{
    public $name;
    public $type;
    public $description;
    public $ruleName;
    public $data;

    public function rules()
    {
        return [
            [['name'], 'string'],
            [['type'], 'in', 'range' => [1,2]],
        ];
    }

    //标签
    public function attributeLabels()
    {
        return [
            'name' => '条目名称',
            'type' => '条目类型',
            'description' => '条目描述',
            'ruleName' => '规则名',
            'data' => '数据',
        ];
    }



    public function search($params)
    {
        $authManager = Yii::$app->authManager;
        $this->load($params);

        //获取类型
        if ($this->type == Item::TYPE_ROLE) {
            $items = $authManager->getRoles();
        } elseif($this->type == Item::TYPE_PERMISSION) {
            $items = $authManager->getPermissions();
        }else{
            $items = $authManager->getRoles() + $authManager->getPermissions();
        }

        //获取like名字
        if ($this->validate() && !empty($this->name)) {

            $search = mb_strtolower(trim($this->name)); //条目名称
            foreach ($items as $name => $item) {

                $f = mb_strpos(mb_strtolower($item->name), $search) !== false;
                if (!$f) {
                    unset($items[$name]);
                }
            }
        }

        return new ArrayDataProvider([
            'allModels' => $items,
        ]);
    }
}