<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\Role as Authenticatable;

class Role extends Model
{
	use Notifiable;

	 protected $fillable = [
    'id',
    'name',
    'slug',
    'description',
    'level',
];

	public function permissions(){
        return $this->belongsToMany('App\Permissions');
    }
    public function editrights($id){
    	return view('user.rolesedit')->with(['role'=>Role::find($id),'permissions'=>Permissions::all()]);
    }
    public function hasAnyPermission($permission){
        return null !== $this->permissions()->where('name',$permission)->first();
    }

    public function hasAnyPermissions($permissions){
        return null !== $this->permissions()->whereIn('name',$permissions)->first();
    }
}
