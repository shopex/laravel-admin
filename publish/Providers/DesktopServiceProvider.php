<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DesktopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share("app_name", config('app.name','Admin'));
        
        view()->share('app_menus', [
    		['label'=>'了解系统', 'guest-only'=>true, 'link'=>'/'],
            ['label'=>'首页', 'link'=>'/home', 'user-only'=>true],
    		['label'=> '菜单组一', 'user-only'=>true, 'items'=>[
            		['label'=>'菜单', 'link'=>'/', 'icon'=>'glyphicon-search'],
                    ['label'=>'菜单', 'link'=>'/'],
                    [],
        			['label'=>'菜单', 'link'=>'/'],
                    ['label'=>'菜单', 'link'=>'/'],
        			['label'=>'菜单', 'link'=>'/'],
        			['label'=>'菜单', 'link'=>'/'],
                    ['label'=>'菜单', 'link'=>'/'],
    		    ]
            ],
        	['label'=> '菜单组二', 'user-only'=>true, 'items'=>[
				    ['label'=>'菜单', 'link'=>'/'],
				    [],
        			['label'=>'菜单', 'link'=>'/'],
        			['label'=>'菜单', 'link'=>'/'],
        			[],
        			['label'=>'菜单', 'link'=>'/'],
        			['label'=>'菜单', 'link'=>'/'],
        		]
            ],
        	['label'=> '菜单三', 'user-only'=>true, 'link'=>'/' ],
            ['label'=>'文档', 'link'=>'/'],
            [
                'label'=> '用户权限', 'user-only'=>true, 
                'items'=>[
                        ['label'=>'用户管理', 'link'=>'/admin/users'],
                        ['label'=>'角色管理', 'link'=>'/admin/roles'],
                        ['label'=>'权限管理', 'link'=>'/admin/permissions'],
                    ]
            ],
            ['label'=>'开发者工具', 'user-only'=>true, 
                'items'=>[
                    ['label'=>'代码生成', 'link'=>'/admin/generator'],
                    
                ]
            ],
        ]);

        view()->share('searchbar', [
                ['label'=>'搜索用户', 'action'=>'/search/user', 'regexp'=>'[a-z0-9\.\_\+\-]'],
                ['label'=>'搜索手机号', 'action'=>'/search/phone', 'regexp'=>'^[0-9\s]+$'],
                ['label'=>'搜索邮箱', 'action'=>'/search/email', 'regexp'=>'^[a-z0-9\.\_\+\-]+@[a-z0-9\.\_\-]+$'],
            ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
