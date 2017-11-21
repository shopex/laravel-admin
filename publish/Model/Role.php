<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label', 'permissions', 'datas'];

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return json_decode($this->permissions,1);
    }
    public function datas()
    {
        return json_decode($this->datas,1);
    }
    public function initdata(){
        $this->permissions  = json_decode($this->permissions,1);
        $this->datas        = json_decode($this->datas,1);
    }
    /**
     * Grant the given permission to a role.
     *
     * @param  Permission $permission
     *
     * @return mixed
     */
    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }
}
