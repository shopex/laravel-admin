<?php

namespace Shopex\LubanAdmin\Traits;

trait HasRoles
{
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(\App\Role::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     *
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            \App\Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }
            return false;
        }
        //is object
        return !!$role->intersect($this->roles)->count();
    }
    /**
     * 验证是否是超级管理员
     *
     *
     * @return boolean
     */
    public function isAdmin(){
        return $this->roles->contains('name', 'admin');
    }
    /**
     * 验证是否具有操作model的权限，
     * model权限需要开发者定义，并在model中使用
     *
     * @param  $modelName  string
     *
     * @return Model
     */
    public function CanModel($modelname){
        $model = new $modelname();
        return $model;
        if ($this->isAdmin()) {
            return $model;
        }
        foreach ($this->roles as $key => $role) {
            $permissions = $role->permissions()->where('type','model')->where('model',$modelname)->get();
            foreach ($permissions as $per) {
                if (is_array($per->filter) && $per->filter) {
                    foreach ($per->filter as $value) {
                       $model = $model->$value['scope']($value['value']);
                    }
                }
            }
        }
        return $model;
    }
    /**
     * 验证用户权限，
     * 所有的路由都应该被定义权限，否则将无法访问
     *
     * @param  route as  $permission
     *
     * @return boolean
     */
    public function hasPermissions($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }
        try {
            $permissions = \App\Permission::where("name",$permission)->firstOrFail();
            return $this->hasPermission($permissions);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return false;
        }        
    }
    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     *
     * @return boolean
     */
    public function hasPermission(\App\Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }
}
