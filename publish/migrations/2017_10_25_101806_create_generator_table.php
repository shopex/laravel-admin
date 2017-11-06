<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneratorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generator', function(Blueprint $table) {
            $table->increments('id');
            $table->string('model_title')->comment('模型名称');
            $table->string('crud_name')->comment('模型');
            $table->string('model_type')->comment('模型类型');
            $table->string('model_namespace')->comment('模型命名空间');
            $table->string('controller_namespace')->comment('控制器命名空间');
            $table->string('route_group')->comment('路由组前缀');
            $table->string('view_path')->comment('视图路径');
            $table->string('is_route')->comment('生成路由');
            $table->string('is_view')->comment('生成视图');
            $table->string('is_controller')->comment('生成控制器');
            $table->string('is_migration')->comment('生成Migration');
            $table->text('fields')->comment('字段');
            $table->text('files')->comment('文件')->nullable();
            $table->string('staging')->comment('是否暂存');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('generator');
    }
}
