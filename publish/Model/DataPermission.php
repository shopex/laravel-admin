<?php

namespace App;

use App\Scopes\DataScope;
use Illuminate\Database\Eloquent\Model;

trait DataPermission
{
	/**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DataScope(self::class) );
    }
}