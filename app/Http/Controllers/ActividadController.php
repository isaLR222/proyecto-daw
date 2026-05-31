<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Contenido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actividad = Actividad::where('user_id', auth()->id())->with('contenido')->latest()->get();
        return view('actividad.index', compact('actividad'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->json()->all();

    $validated = validator($data, [
        'tipo' => 'required|string',
        'api_id' => 'required',
        'estado' => 'nullable|string',
        'valoracion' => 'nullable|integer|min:1|max:5',
        'comentario' => 'nullable|string',
        'favorito' => 'nullable|boolean'
    ])->validate();

    // Buscar contenido existente por ID de API
    if ($validated['tipo'] === 'pelicula') {
        $contenido = Contenido::where('detalles->tmdb_id', $validated['api_id'])->first();
    } else {
        $contenido = Contenido::where('detalles->openlibrary_id', $validated['api_id'])->first();
    }

    // Si no existe, crearlo
    if (!$contenido) {
        if ($validated['tipo'] === 'pelicula') {

            $apiData = Http::get("https://api.themoviedb.org/3/movie/{$validated['api_id']}", [
                'api_key' => env('TMDB_KEY'),
                'language' => 'es-ES'
            ])->json();

            $contenidoData = [
                'titulo' => $apiData['title'],
                'tipo' => 'pelicula',
                'fecha_lanzamiento' => $apiData['release_date'] ?? null,
                'sinopsis' => $apiData['overview'] ?? null,
                'categoria' => isset($apiData['genres'])
                    ? implode(', ', array_column($apiData['genres'], 'name'))
                    : null,
                'detalles' => [
                    'poster' => $apiData['poster_path'] ?? null,
                    'tmdb_id' => $validated['api_id']
                ]
            ];
        }

        if ($validated['tipo'] === 'libro') {

            $apiData = Http::get("https://openlibrary.org/works/{$validated['api_id']}.json")->json();

            $coverId = $apiData['covers'][0] ?? null;
            $thumbnail = $coverId
                ? "https://covers.openlibrary.org/b/id/{$coverId}-L.jpg"
                : null;

            $descripcion = $apiData['description']['value']
                ?? $apiData['description']
                ?? null;

            $contenidoData = [
                'titulo' => $apiData['title'] ?? 'Título desconocido',
                'tipo' => 'libro',
                'fecha_lanzamiento' => $apiData['created']['value'] ?? null,
                'sinopsis' => $descripcion,
                'categoria' => $apiData['subjects'][0] ?? null,
                'detalles' => [
                    'thumbnail' => $thumbnail,
                    'openlibrary_id' => $validated['api_id']
                ]
            ];
        }

        $contenido = Contenido::create($contenidoData);
    }

    // buscar la actividad que haya
    $actividad = Actividad::where('user_id', auth()->id())
        ->where('contenido_id', $contenido->id)
        ->first();

    Actividad::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'contenido_id' => $contenido->id
        ],
        [
            'estado' => $validated['estado'] ?? ($actividad->estado ?? 'no_visto'),
            'valoracion' => $validated['valoracion'] ?? ($actividad->valoracion ?? null),
            'favorito' => $validated['favorito'] ?? ($actividad->favorito ?? false),
            'comentario' => array_key_exists('comentario', $validated)
                ? $validated['comentario']
                : ($actividad->comentario ?? null)
        ]
    );

    return response()->json(['ok' => true]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $actividad = Actividad::where('user_id', auth()->id())->with('contenido')->findOrFail($id);

        return view('actividad.show', compact('actividad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $actividad = Actividad::where('user_id', auth()->id())->findOrFail($id);
        return view('actividades.edit', compact('actividad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $actividad = Actividad::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'estado' => 'required|string',
            'valoracion' => 'nullable|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'favorito' => 'nullable|boolean'
        ]);

        $actividad->update($data);

        return redirect()->route('contenido.mios.show', $actividad->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $actividad = Actividad::where('user_id', auth()->id())->findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividad.index');
    }

    public function favoritos()
{
    $favoritos = Actividad::with('contenido')
        ->where('user_id', auth()->id())
        ->where('favorito', true)
        ->get();

    return view('actividades.favoritos', compact('favoritos'));
}

}