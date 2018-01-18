<?php

namespace VoxRedirects;

use Illuminate\Events\Dispatcher;
use YogurtDesign\Vox\Models\Menu;
use YogurtDesign\Vox\Models\MenuItem;
use YogurtDesign\Vox\Models\Permission;
use YogurtDesign\Vox\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use VoxRedirects\Http\Middleware\VoxRedirectMiddleware;
use Illuminate\Support\ServiceProvider;

class VoxRedirectServiceProvider extends ServiceProvider
{

	private $models = [
		'VoxRedirect'
	];

	public function register()
	{
		app(Dispatcher::class)->listen('vox.admin.routing', [$this, 'addRedirectRoutes']);
		app(Dispatcher::class)->listen('vox.menu.display', [$this, 'addRedirectMenuItem']);
	}

	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'vox.redirects');
		$this->loadModels();

		// Add the redirect middleware that will handle all redirects
		$this->app['Illuminate\Contracts\Http\Kernel']->prependMiddleware(VoxRedirectMiddleware::class);
	}

	public function addRedirectRoutes($router)
    {
        $namespacePrefix = '\\VoxRedirects\\Http\\Controllers\\';
        $router->get('redirects', ['uses' => $namespacePrefix.'VoxRedirectController@browse', 'as' => 'redirects']);
        $router->get('redirects/add', ['uses' => $namespacePrefix.'VoxRedirectController@add', 'as' => 'redirects.add']);
    	$router->post('redirects/add', ['uses' => $namespacePrefix.'VoxRedirectController@add_post', 'as' => 'redirects.add.post']);
    	$router->get('redirects/{id}/edit', ['uses' => $namespacePrefix.'VoxRedirectController@edit', 'as' => 'redirects.edit']);
    	$router->post('redirects/edit', ['uses' => $namespacePrefix.'VoxRedirectController@edit_post', 'as' => 'redirects.edit.post']);
    	$router->delete('redirects/delete', ['uses' => $namespacePrefix.'VoxRedirectController@delete', 'as' => 'redirects.delete']);
	
    }

	public function addRedirectMenuItem(Menu $menu)
	{
	    if ($menu->name == 'admin') {
	        $url = route('vox.redirects', [], false);
	        $menuItem = $menu->items->where('url', $url)->first();
	        if (is_null($menuItem)) {
	            $menu->items->add(MenuItem::create([
	                'menu_id'    => $menu->id,
	                'url'        => $url,
	                'title'      => 'Redirects',
	                'target'     => '_self',
	                'icon_class' => 'directions',
	                'color'      => null,
	                'parent_id'  => null,
	                'order'      => 99,
	            ]));
	            $this->ensurePermissionExist();
	            $this->addRedirectsTable();
	        }
	    }
	}

	private function loadModels(){
		foreach($this->models as $model){
			$namespacePrefix = '\\VoxRedirects\\Models\\';
			if(!class_exists($namespacePrefix . $model)){
				@include(__DIR__.'/Models/' . $model . '.php');
			}
		}
	}

	protected function ensurePermissionExist()
    {
        $permissions = [
        	Permission::firstOrNew(['key' => 'browse_redirects', 'table_name' => 'redirects']),
        	Permission::firstOrNew(['key' => 'edit_redirects', 'table_name' => 'redirects']),
        	Permission::firstOrNew(['key' => 'add_redirects', 'table_name' => 'redirects']),
        	Permission::firstOrNew(['key' => 'delete_redirects', 'table_name' => 'redirects'])
        ];

        foreach($permissions as $permission){
	        if (!$permission->exists) {
	            $permission->save();
	            $developer_role = Role::where('name', 'developer')->first();
	            $admin_role = Role::where('name', 'admin')->first();
	            if (!is_null($admin_role)) {
                    $admin_role->permissions()->attach($permission);
	            }
	            if (!is_null($developer_role)) {
                    $developer_role->permissions()->attach($permission);
	            }
	        }
	    }
    }

    private function addRedirectsTable(){
    	if(!Schema::hasTable('vox_redirects')){
    		Schema::create('vox_redirects', function (Blueprint $table) {
	            $table->increments('id');
				$table->string('from')->unique();
				$table->string('to');
				$table->string('type', 3);
				$table->timestamps();
	        });
	    }
    }
}