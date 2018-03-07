<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shopex\LubanAdmin\Permission\Data;
class DataScope implements Scope
{
	/**
	 * 初始化模型
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($modelName)
	{
        $this->modelName = $modelName;
	}
    /**
     * 应用作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->user()->isAdmin()) {
            return $builder;
        }
        $data = new Data($this->modelName);
        $result = $data->check();
        if ($result) {
            foreach ($result as $role_id => $whereGroup) {
                 $builder->where(function ($builder) use ($whereGroup) {
                    foreach ($whereGroup as $key => $group) {
                        foreach ($group as $field => $value) {
                            if (!is_array($value)) {
                                $value = explode(",",$value);
                            }
                            $builder->orWhereIn($field, $value);
                        }
                    }
                });
            }
        }
        return $builder;
    }
}