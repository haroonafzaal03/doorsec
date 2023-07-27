<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'dashboard'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function roles(){
        return $this->belongsToMany('App\Role');
    }
    public function hasAnyRole($role){
      // dd($this->roles()->where('name',$role)->first());
        return $this->roles()->where('name',$role)->first();
    }
    public function return_role_detil($role){
        $role = $this->roles()->where('name',$role)->first();
        //dd($role->permissions);
        return $role->permissions;//$this->roles()->where('name',$role)->first();
    }
    public function hasAnyRoles($roles){
        return $this->roles()->whereIn('name',$roles)->first();
    }

    public function hasPermission($slug)
    {
        foreach($this->roles as $role)
        {
            foreach($role->permissions as $permission)
            {

                if($slug===$permission->slug)
                {
                    return true;
                }
            }
        }
        return false;
    }

}