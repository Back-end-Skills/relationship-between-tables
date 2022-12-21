<?php

use Illuminate\Support\Facades\Route;

use App\Models\{
    User,
    Preference
} ;

Route::get('/one-to-one', function (){
    // $user = User::first(s);
    $user = User::find(7);
    $user = User::with('preference')->find(7);

    // $user->preference()->create([
    //     'background_color' => 'blue'
    // ]);
    $data = [
        'background_color' => 'red'
    ];

    if($user->preference)
    {
        $user->preference->update($data);
    } else {
        $pre = new Preference($data);
        $user->preference()->save($pre);
    }
    // if($user->preference)
    // {
    //     $user->preference->update($data);
    // } else {
    //     $user->preference()->create();
    // }

    $user->refresh();

    $user->preference->delete();
    $user->refresh();

    dd($user->preference());
});

Route::get('/', function () {
    return view('welcome');
});
