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

    public function peliculas()
    {
        $apiKey = env('TMDB_KEY');

        $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => $apiKey,
            'language' => 'es-ES'
        ])->json();

        $peliculas = collect($response['results'] ?? [])->map(function ($p) {
            return [
                'id' => $p['id'],
                'titulo' => $p['title'],
                'descripcion' => $p['overview'],
                'imagen' => "https://image.tmdb.org/t/p/w500" . $p['poster_path']
            ];
        });
        return response()->json($peliculas);
    }

    public function libros()
{
    $url = "https://openlibrary.org/search.json?language=spa&q=bestseller";

    $response = Http::get($url);

    $docs = $response->json()['docs'] ?? [];

    return collect($docs)->map(function ($d) {
        return [
            'id' => $d['key'] ?? null,
            'titulo' => $d['title'] ?? 'Sin título',
            'imagen' => isset($d['cover_i'])
                ? "https://covers.openlibrary.org/b/id/{$d['cover_i']}-L.jpg"
                : null
        ];
    });
}


  public function misContenidos()
{
    $mios = Contenido::where('user_id', auth()->id())->get();

    $data = $mios->map(function ($c) {
        return [
            'id' => $c->id,
            'titulo' => $c->titulo,
            'descripcion' => $c->sinopsis ?? 'Sin descripción',
            'imagen' => $c->imagen ?? '/img/no-image.png'
        ];
    });

    return response()->json($data);
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
