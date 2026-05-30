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
    
    $request->merge($request->json()->all());

    $data = $request->validate([
        'tipo' => 'required|string',
        'api_id' => 'required|string',
        'estado' => 'nullable|string',
        'valoracion' => 'nullable|integer|min:1|max:5',
        'comentario' => 'nullable|string',
        'favorito' => 'nullable|boolean'
    ]);

    
    if ($data['tipo'] === 'pelicula') {

    $apiData = Http::get("https://api.themoviedb.org/3/movie/{$data['api_id']}", [
        'api_key' => env('TMDB_KEY'),
        'language' => 'es-ES'
    ])->json();

    $generos = isset($apiData['genres'])
        ? implode(', ', array_column($apiData['genres'], 'name'))
        : null;

    $contenidoData = [
        'titulo' => $apiData['title'],
        'tipo' => 'pelicula',
        'fecha_lanzamiento' => $apiData['release_date'] ?? null,
        'sinopsis' => $apiData['overview'] ?? null,
        'categoria' => $generos,
        'detalles' => [
            'poster' => $apiData['poster_path'] ?? null,
            'tmdb_id' => $data['api_id']
        ]
    ];

    $contenido = Contenido::updateOrCreate(
        ['detalles->tmdb_id' => $data['api_id']],
        $contenidoData
    );
}


    if ($data['tipo'] === 'libro') {

        $apiData = Http::get("https://openlibrary.org/works/{$data['api_id']}.json")->json();

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
                'openlibrary_id' => $data['api_id']
            ]
        ];

        $contenido = Contenido::firstOrCreate(
            ['detalles->openlibrary_id' => $data['api_id']],
            $contenidoData
        );
    }

    Actividad::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'contenido_id' => $contenido->id
        ],
        [
            'estado' => $data['estado'] ?? 'no_visto',
            'valoracion' => $data['valoracion'] ?? null,
            'comentario' => $data['comentario'] ?? null,
            'favorito' => $request->has('favorito')

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

        return view('actividades.show', compact('actividad'));
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

        $actividad->update([
    'estado' => $data['estado'],
    'valoracion' => $data['valoracion'],
    'comentario' => $data['comentario'],
    'favorito' => $request->has('favorito'),
]);

        return redirect()->route('contenido.mios.show', $actividad->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $actividad = Actividad::where('user_id', auth()->id())->findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividades.index');
    }
}