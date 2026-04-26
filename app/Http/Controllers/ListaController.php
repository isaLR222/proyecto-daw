<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use Illuminate\Http\Request;
use App\Models\Contenido;
use Illuminate\Support\Facades\Http;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listas=Lista::where('user_id', auth()->id())->get();
        return view('lista.index', compact('listas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lista.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'nombre'=>'required|string|max:120',
            'descripcion'=>'nullable|string',
        ]);

        $data['user_id']=auth()->id();
        $lista= Lista::create($data);

        return redirect()->route('listas.show',$lista->id)->with('exito','lista creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lista=Lista::where('user_id',auth()->id())->findOrFail($id);
        $contenido=$lista->contenido; //por la relacion N:M
        return view('listas.show', compact('lista','contenido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $lista = Lista::where('user_id', auth()->id())->findOrFail($id);
        return view('listas.edit', compact('lista'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lista = Lista::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $lista->update($data);

        return redirect()->route('listas.show', $lista->id)->with('exito', 'Lista actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lista = Lista::where('user_id', auth()->id())->findOrFail($id);
        $lista->delete();

        return redirect()->route('listas.index')->with('exito', 'Lista eliminada.');
    }

    public function añadirContenido(Request $request, $listaId)
    {
        $lista = Lista::where('user_id', auth()->id())->findOrFail($listaId);

        $tipo = $request->input('tipo'); // 'pelicula' o 'libro'
        $apiId = $request->input('api_id');

        // 1. Obtener datos desde la API
        if ($tipo === 'pelicula') {
            $apiData = Http::get("https://api.themoviedb.org/3/movie/{$apiId}", [
                'api_key' => env('TMDB_KEY'),
                'language' => 'es-ES'
            ])->json();

            $data = [
                'titulo' => $apiData['title'],
                'tipo' => 'pelicula',
                'fecha_lanzamiento' => $apiData['release_date'] ?? null,
                'sinopsis' => $apiData['overview'] ?? null,
                'categoria' => null,
                'detalles' => [
                    'poster' => $apiData['poster_path'] ?? null,
                    'tmdb_id' => $apiId
                ]
            ];
        }

        if ($tipo === 'libro') {
            $apiData = Http::get("https://www.googleapis.com/books/v1/volumes/{$apiId}")
                ->json()['volumeInfo'];

            $data = [
                'titulo' => $apiData['title'],
                'tipo' => 'libro',
                'fecha_lanzamiento' => $apiData['publishedDate'] ?? null,
                'sinopsis' => $apiData['description'] ?? null,
                'categoria' => $apiData['categories'][0] ?? null,
                'detalles' => [
                    'thumbnail' => $apiData['imageLinks']['thumbnail'] ?? null,
                    'google_id' => $apiId
                ]
            ];
        }

        // 2. Guardar en BD si no existe
        $contenido = Contenido::firstOrCreate(
            ['titulo' => $data['titulo'], 'tipo' => $data['tipo']],
            $data
        );

        // 3. Añadir a la lista (pivot)
        $lista->contenido()->syncWithoutDetaching([$contenido->id]);

        return back()->with('success', 'Contenido añadido a la lista.');
    }

    /**
     * Quitar contenido de una lista.
     */
    public function quitarContenido($listaId, $contenidoId)
    {
        $lista = Lista::where('user_id', auth()->id())->findOrFail($listaId);
        $lista->contenido()->detach($contenidoId);

        return back()->with('success', 'Contenido eliminado de la lista.');
    }
}
