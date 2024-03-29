<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         //Broadcast::routes();
          Broadcast::routes(['middleware' => ['web', 'auth:api,tutor,admin']]);
        // Broadcast::routes(['middleware' => ['auth:tutor']]);
        // $router->post('/guard/auth/broadcasting', function(Request $req) {
        //     Broadcast::auth($req);
        // });
        require base_path('routes/channels.php');
    }
}
