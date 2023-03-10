<?php

use Illuminate\Support\Facades\Route;

use App\Models\{
    User,
    Preference,
    Course,
    Module,
    Permission
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
    $user = User::find(1);

    $modules = $course->modules;

    dd($modules);
});

Route::get('/many-to-many', function () {
    //Create permissions
    //dd(Permission::create(['name'=>'menu_03']));

    // get user
    $user = User::with('permissions')->find(5);     //$user = User::find(1);

    //vincular permissions
    $permission = Permission::find(1);
    //$user->permissions()->save($permission);

    //vincular várias permissions
    /*$user->permissions()->saveMany([
        Permission::find(1),
        Permission::find(3),
        Permission::find(2)
    ]);*/
    //$user->permissions()->sync([2]); //injetar na table permission_user id 2 da table permissions 
    
    //varias vezes a mesma permissão
    //$user->permissions()->attach([1]);
    
    //remover permissoes da table permission_user passando o permission_id
    $user->permissions()->detach([1]);


    $user->refresh();

    // get permissions
    dd($user->permissions);

});

Route::get('/many-to-many-pivot', function () {
    $user = User::with('permissions')
                    ->find(1);
    //buscando permissions do user 1
    $data_permissions_user=$user->permissions;
    
    //dd($user);

    echo "<br>Name -table-users: {$user->name} <hr>";

    echo "Permissions da table permissions para => {$user->name} <br>";
    foreach($user->permissions as $permission)
    {      
        echo "id={$permission->id} Nome = {$permission->name} - permission_id={$permission->pivot->permission_id} - User_id={$permission->pivot->user_id}  <br>";
    }
    echo "<hr>";

   

    //imprimir todos os relacionamentos
    foreach($data_permissions_user as $permission)
    {
        echo "Id: {$permission->id}  <br>";

    }
    //dd($data_permissions_user); 
    
    //exibe todo o object 
    foreach($data_permissions_user as $permission)
    {
        echo "Id: $permission  <br>";

    }
});

Route::get('/many-to-many-pivot-add', function () {
    /*
    * adicionar items an table pivot 
    *
    */
    $user = User::with('permissions')->find(5);
    $user->permissions()->attach([
        1 => ['active' => false], 
        3 => ['active' => false] 
        
    ]);

    $user->refresh();
  
    $data_permissions_user=$user->permissions;
    
    

    echo "<br>Name -table-users: {$user->name} <hr>";

    echo "Permissions da table permissions para => {$user->name} <br>";
    foreach($user->permissions as $permission)
    {      
        echo "id={$permission->id} Nome = {$permission->name} - permission_id={$permission->pivot->permission_id} - User_id={$permission->pivot->user_id}  <br>";
    }
    echo "<hr>";

   
});

Route::get('/', function () {
    return view('welcome');
});
