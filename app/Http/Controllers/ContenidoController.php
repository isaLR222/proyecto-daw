<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use Illuminate\Support\Facades\Http;

class ContenidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) //porque quiero hacer un radio para selecionar entre peliculas, libros o "mi contenido"
    {
        $filtro = $request->input('filtro', 'pelis'); 
        $peliculas = [];
        $libros = [];
        $mios = [];

        switch ($filtro) {

            case 'pelis':
                $peliculas = Http::get('https://api.themoviedb.org/3/movie/popular', [
                    'api_key' => env('TMDB_KEY'),
                    'language' => 'es-ES'
                ])->json()['results'] ?? [];
                break;

            case 'libros':
                $libros = Http::get('https://www.googleapis.com/books/v1/volumes', [
                    'q' => 'popular books',
                    'langRestrict' => 'es'
                ])->json()['items'] ?? [];
                break;

            case 'mios':
                $mios = Contenido::whereHas('actividad', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                    ->orWhereHas('listas', function ($q) {
                        $q->where('user_id', auth()->id());
                    })
                    ->get();
                break;
        }

        return view('contenido.index', compact('filtro', 'peliculas', 'libros', 'mios'));
    }

    public function guardarDesdeAPI(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'tipo' => 'required|string',
            'fecha_lanzamiento' => 'nullable|string',
            'sinopsis' => 'nullable|string',
            'categoria' => 'nullable|string',
            'detalles' => 'nullable|array'
        ]);

        Contenido::create($data);

        return redirect()->route('index')->with('success', 'Contenido añadido a tu biblioteca.');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contenido = Contenido::findOrFail($id);
        return view('contenido.show', compact('contenido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
