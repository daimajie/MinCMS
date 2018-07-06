<?php
namespace app\models\rbac;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class SearchAuthRule extends Model
{
    public $name;

    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }


    public function search($params)
    {
        //获取auth
        $authManager = Yii::$app->authManager;

        $models = [];
        $included = !($this->load($params) && $this->validate() && trim($this->name) !== '');

        foreach ($authManager->getRules() as $name => $item) {

            if (
                //数据验证成功 并且 规则名包含搜索的关键字
                $included || stripos($item->name, $this->name) !== false
            ) {
                $models[$name] = new AuthRule($item);
            }
        }

        return new ArrayDataProvider([
            'allModels' => $models,
        ]);
    }
}