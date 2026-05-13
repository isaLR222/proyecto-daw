<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('contenido.index');
});

Route::middleware('auth')->group(function () {

    /*Route::get('/contenido', function () {
        return view('contenido.index');
    })->name('contenido.index');*/

   

    //contenido
        //index
     Route::get('/contenido', [ContenidoController::class, 'index'])
    ->name('contenido.index');
        //si esta en la BD
    Route::get('contenido/{contenido}',[ContenidoController::class, 'showMiContenido'])
        ->name('contenido.show')
        ->whereNumber('contenido');
        //de la api peli
    Route::get('/contenido/pelicula/{id}', [ContenidoController::class, 'showPeliculaAPI'])
    ->name('contenido.pelicula.show')
    ->whereNumber('id');
        //de la api libro
    Route::get('/contenido/libro/{id}', [ContenidoController::class, 'showLibroAPI'])
    ->name('contenido.libro.show');

    //listas
        //ver todas las listas
    Route::get('/listas',[ListaController::class, 'index'])
        ->name('listas.index');
        //crear lista
    Route::get('/listas/create',[ListaController::class, 'create'])
        ->name('listas.create');
        //guardar
    Route::post('/listas',[ListaController::class, 'store'])
        ->name('listas.store');
        //ver la lista
    Route::get('/listas/{lista}',[ListaController::class, 'show'])
        ->name('listas.show')
        ->whereNumber('lista');
        //editarla
    Route::get('/listas/{lista}/edit',[ListaController::class, 'edit'])
        ->name('listas.edit')
        ->whereNumber('lista');
        //actualizar
    Route::put('/listas/{lista}',[ListaController::class, 'update'])
        ->name('listas.update')
        ->whereNumber('lista');
        //eliminar
    Route::delete('listas/{lista}',[ListaController::class, 'destroy'])
        ->name('listas.destroy')
        ->whereNumber('lista');

    //lActividad
        //Ver actividad usuario
    Route::get('/actividad', [ActividadController::class,'index'])
        ->name('actividades.index');
        //store
    Route::post('/actividad',[ActividadController::class, 'store'])
        ->name('actividades.store');
        //La actividad de uno en concreto
    Route::get('actividad/{actividad}',[ActividadController::class, 'show'])
        ->name('actividades.show')
        ->whereNumber('actividad');
        //editar
    Route::get('actividad/{actividad}/edit',[ActividadController::class, 'edit'])
        ->name('actividades.edit')
        ->whereNumber('actividad');
        //actualizar
    Route::put('actividad/{actividad}',[ActividadController::class,'update'])
        ->name('actividades.update')
        ->whereNumber('actividad');
        //eliminar
    Route::delete('actividad/{actividad}',[ActividadController::class, 'destroy'])
        ->name('actividades.destroy')
        ->whereNumber('actividad');
    
    //perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    //apis
Route::prefix('api')->group(function () {
    Route::get('/peliculas', [ContenidoController::class, 'peliculas']);
    Route::get('/libros', [ContenidoController::class, 'libros']);
    Route::get('/mis-contenidos', [ContenidoController::class, 'misContenidos']);
});

require __DIR__.'/auth.php';
    