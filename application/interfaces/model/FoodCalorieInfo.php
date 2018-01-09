<?php
namespace app\interfaces\model;

use think\Loader;
use think\Model;

class FoodCalorieInfo extends Model
{
    protected $table = "food_calorie";

    /**
     * 查找食物卡路里
     * @param String $food
     * @return decimal $calorie
     */
    public function getFoodCalorie($food)
    {
        $caloieinfo = $this->where('food', $food)
            ->field(['calorie'])
            ->limit(1)->find();
        if (!empty($caloieinfo)) {
            $calorie=$caloieinfo->data['calorie'];
        } else {
            $calorie = 0;
        }
        return $calorie;
    }
}
