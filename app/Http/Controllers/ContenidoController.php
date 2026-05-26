<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use Illuminate\Support\Facades\Http;
use App\Models\Actividad;

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
        $peliculas = collect();

        for ($page = 1; $page <= 5; $page++) {
            $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
                'api_key' => $apiKey,
                'language' => 'es-ES',
                'page' => $page
            ])->json();

            $peliculas = $peliculas->merge($response['results'] ?? []);
        }

        return $peliculas->map(function ($p) {
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
    public function indexMios()
    {
        $contenidos = Contenido::where('user_id', auth()->id())->get();

        return view('contenido.mios-index', compact('contenidos'));
    }


    /*acceder al contenido guardado en BD*/
    public function misContenidos()
    {
        return response()->json(
            Contenido::where('user_id', auth()->id())
                ->get()
                ->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'titulo' => $c->titulo,
                        'descripcion' => $c->sinopsis ?? 'Sin descripción',
                        'imagen' => $c->detalles['imagen'] ?? '/img/no-image.png'
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
    public function showMiContenido($id)
    {
        $contenido = Contenido::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        return view('contenido.show-mi-contenido', compact('contenido'));
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

        $contenido = Contenido::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'tipo' => 'pelicula',
                'titulo' => $pelicula['titulo'],
            ],
            [
                'sinopsis' => $pelicula['descripcion'],
                'fecha_lanzamiento' => $pelicula['fecha'],
                'categoria' => null,
                'detalles' => [
                    'tmdb_id' => $id,
                    'imagen' => $pelicula['imagen']
                ]
            ]
        );

        $actividad = Actividad::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'contenido_id' => $contenido->id
            ],
            [
                'estado' => 'no_visto',
                'valoracion' => 0,
                'comentario' => null,
                'favorito' => false
            ]
        );

        return view('contenido.show-pelicula', compact('pelicula', 'actividad'));
    }
    public function showLibroAPI($id)
    {
        $id = ltrim($id, '/');
        $url = "https://openlibrary.org/{$id}.json";

        $data = Http::timeout(20)->get($url)->json();

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
            'generos' => $data['subjects'] ?? [],
            'fecha' => $data['created']['value'] ?? null,
        ];

        // Buscar o crear contenido por openlibrary_id
        $contenido = Contenido::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'tipo' => 'libro',
                'detalles->openlibrary_id' => $id,
            ],
            [
                'titulo' => $libro['titulo'],
                'sinopsis' => $libro['descripcion'],
                'fecha_lanzamiento' => $libro['fecha'],
                'categoria' => null,
                'detalles' => [
                    'openlibrary_id' => $id,
                    'imagen' => $libro['imagen']
                ]
            ]
        );

        // Actividad
        $actividad = Actividad::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'contenido_id' => $contenido->id
            ],
            [
                'estado' => 'no_visto',
                'valoracion' => 0,
                'comentario' => null,
                'favorito' => false
            ]
        );

        return view('contenido.show-libro', compact('libro', 'actividad'));
    }
    public function destroy($id)
    {
        $contenido = Contenido::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        Actividad::where('contenido_id', $contenido->id)->delete();

        $contenido->delete();

        return redirect()
            ->route('mios.index')
            ->with('success', 'Contenido eliminado correctamente.');
    }
}
