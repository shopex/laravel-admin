<?php
namespace Shopex\LubanAdmin\Permission;

use Shopex\LubanAdmin\Permission\Configs;
use Config;
use Auth;
use Shopex\LubanAdmin\Permission\Check;

class Menu
{
    public $routes = [];
    public $showMenus = [];
    public function initRoutes()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->routes = $user->getCanRoute();
        }
        $this->check = new Check();
        $this->check->prcessPermission();
    }
    /*
        过滤根据权限过滤菜单
     */
    public function show($menus)
    {
        
        $this->initRoutes();

        foreach ($menus as $row) {
            if (array_has($row,'route')) {
                $res = $this->processRouteMenu($row);
                if ($res) {
                    $this->showMenus[] = $res;
                }
            }else if(array_has($row,'items')){
                $res =  $this->processRouteMenus($row['items']);
                if ($res) {
                    $row['items'] = $res;
                    $this->showMenus[] = $row;
                }
            }
        }
        return $this->showMenus;
    }
    public  function processRouteMenus($menus)
    {
        $res = [];
        foreach ($menus as $row) {
            $showrow = $this->processRouteMenu($row);
            if ($showrow) {
                $res[] = $showrow;
            }
        }
        return $res;
    }
    public  function processRouteMenu($row){
        if (isset($row['route'])) {
            if ( $this->check->hasPermission($row['route'],$this->routes) || Auth::user()->isAdmin()) {
                 $params = array_has($row,'params')?$row['params']:[];
                $row['link'] = route($row['route'],$params);
                return $row;
            }
           
        }
        return [];
    }
}