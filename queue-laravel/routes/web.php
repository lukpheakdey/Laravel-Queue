<?php
use App\Jobs\ReconcileAccount;
use Illuminate\Pipeline\Pipeline;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    /* Delay queue 1 mintue */
    /*
    dispatch(function(){
        logger('I have to tell you about the future');
    })->delay(now()->addMinute(1));
    return 'finsihed';
    */

    $user = App\User::first();
    //dispatch(new ReconcileAccount($user));

    ReconcileAccount::dispatch($user);

    $pipeline = app(Pipeline::class);

    $pipeline->send('i am pheakdey')
            ->through([
                function($string, $next) {
                    $string = ucwords($string);
                    return $next($string);
                },
                function ($string, $next) {
                    $string = str_ireplace('hoping', '', $string);
                    return $next($string);
                },
            ])
            ->then(function ($string){
               dump($string);
            });

    return 'Done';

});
