<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use Illuminate\Support\Facades\Http;

class ContenidoController extends Controller
{
    /**
     * Vista principal con filtro (pelis, libros, mios)
     */
    public function index(Request $request)
    {
        $filtro = $request->input('filtro', 'pelis');
        $peliculas = [];
        $libros = [];
        $mios = [];

        switch ($filtro) {

            case 'pelis':
                $peliculas = $this->getPeliculas();
                break;

            case 'libros':
                $libros = $this->getLibros();
                break;

            case 'mios':
                $mios = Contenido::where('user_id', auth()->id())->get();
                break;
        }

        return view('contenido.index', compact('filtro', 'peliculas', 'libros', 'mios'));
    }

    /*api películas*/ 
    public function peliculas()
    {
        return response()->json($this->getPeliculas());
    }

    private function getPeliculas()
    {
        $apiKey = env('TMDB_KEY');

        $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => $apiKey,
            'language' => 'es-ES'
        ])->json();

        return collect($response['results'] ?? [])->map(function ($p) {
            return [
                'id' => $p['id'],
                'titulo' => $p['title'],
                'descripcion' => $p['overview'],
                'imagen' => $p['poster_path']
                    ? "https://image.tmdb.org/t/p/w500{$p['poster_path']}"
                    : null
            ];
        });
    }

    /*api libros*/ 
    public function libros()
    {
        return response()->json($this->getLibros());
    }

    private function getLibros()
    {
        $url = "https://openlibrary.org/search.json?language=spa&q=bestseller";

        $response = Http::get($url)->json();

        return collect($response['docs'] ?? [])->map(function ($d) {
            return [
                'id' => $d['key'] ?? null,
                'titulo' => $d['title'] ?? 'Sin título',
                'imagen' => isset($d['cover_i'])
                    ? "https://covers.openlibrary.org/b/id/{$d['cover_i']}-L.jpg"
                    : null
            ];
        });
    }

    /*acceder al contenido guardado en BD*/
    public function misContenidos()
    {
        $mios = Contenido::where('user_id', auth()->id())->get();

        return response()->json(
            $mios->map(function ($c) {
                return [
                    'id' => $c->id,
                    'titulo' => $c->titulo,
                    'descripcion' => $c->sinopsis ?? 'Sin descripción',
                    'imagen' => $c->imagen ?? '/img/no-image.png'
                ];
            })
        );
    }

    /*Guardar contenido desde API*/
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

    /* Mostrar contenido guardado en BD */
    public function showMiContenido(string $id)
    {
        $contenido = Contenido::findOrFail($id);
        return view('contenido.show', compact('contenido'));
    }

    /* Mostrar película desde api*/
    public function showPeliculaAPI($id)
    {
        $apiKey = env('TMDB_KEY');

        $url = "https://api.themoviedb.org/3/movie/{$id}?api_key={$apiKey}&language=es-ES";

        $data = Http::get($url)->json();

        if (!$data || (isset($data['success']) && $data['success'] === false)) {
            abort(404, "Película no encontrada");
        }

        $pelicula = [
            'id' => $data['id'],
            'titulo' => $data['title'],
            'descripcion' => $data['overview'],
            'imagen' => "https://image.tmdb.org/t/p/w500{$data['poster_path']}",
            'fecha' => $data['release_date'],
            'generos' => array_column($data['genres'], 'name'),
        ];

        return view('contenido.show-pelicula', compact('pelicula'));
    }

    /* Mostrar libro desde api*/
    public function showLibroAPI($id)
    {
        $id = urldecode($id);

        $url = "https://openlibrary.org{$id}.json";

        $data = Http::get($url)->json();

        if (!$data) {
            abort(404, "Libro no encontrado");
        }

        $imagen = isset($data['covers'][0])
            ? "https://covers.openlibrary.org/b/id/{$data['covers'][0]}-L.jpg"
            : null;

        $libro = [
            'id' => $id,
            'titulo' => $data['title'] ?? 'Sin título',
            'descripcion' => $data['description']['value']
                ?? $data['description']
                ?? 'Sin descripción',
            'imagen' => $imagen,
            'temas' => $data['subjects'] ?? [],
            'fecha' => $data['created']['value'] ?? null,
        ];

        return view('contenido.show-libro', compact('libro'));
    }
}
