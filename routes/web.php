<?php

use Illuminate\Support\Facades\Route;

use App\Models\{
    User,
    Preference,
    Course,
    Module
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\RouteUri;

Route::get('/one-to-one', function (){
    // $user = User::first();
    $user = User::find(7);
    $user = User::with('preference')->find(7);   // mais otimizado

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
    /*if($user->preference)
    {
        $user->preference->update($data);
    } else {
        $user->preference()->create();
    }*/

    $user->refresh();

    $user->preference->delete();
    $user->refresh();

    dd($user->preference());
});

Route::get('/one-to-many', function (){
    // $course = Course::create([ 'name'=> 'laravel 9x' ]);
    // $course = Course::first();
    // $course = Course::find(2);
    // $course = Course::with('modules')->first();  //otimizado!
    
    //Retornando tudo : curos, modulos e lições
    // $course = Course::with('modules.lessons')->first();  //otimizado!
    $course = Course::with('modules.lessons')->find(2);  //para o id de curso 2
    //dd($course);

    echo "Id:$course->id Nome:$course->name";
    echo "<br>";
    foreach ($course->modules as $module)
    {
        echo "Módulo Id:$module->id Course Id:$module->course_id Nome:$module->name <br>";

        foreach ($module->lessons as $lesson)
        {
            echo "Aulas Id:$lesson->id $lesson->name ";
        }
    }

    //$course->modules()->get(); // não recomendado
    
    //Inserindo um novo modulo
    $data = ['name' => 'Módulo x3'];
    // $course->modules()->create($data);

    //Alterando 
    // Module::find(2)->update();

    //Lessons


    $modules = $course->modules;

    dd($modules);
});

Route::get('/', function () {
    return view('welcome');
});
