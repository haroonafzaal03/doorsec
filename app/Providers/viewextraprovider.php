<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\user;
use App\Role;

class viewextraprovider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
		try{
        Blade::if('hasrole',function($slug=''){
            if(Auth::user()){
				$roles = Auth::user()->roles;
				foreach ($roles as $role) {
				  
					if(Auth::user()->return_role_detil($role->name)->pluck('slug')->contains($slug))
						{
							return true;
						}   
				}
			}else{
				return redirect('/login');
			}
            return false;
        });
		}catch(\Exception $e){
    // catch code
		return redirect('/login');
}
    }
}
