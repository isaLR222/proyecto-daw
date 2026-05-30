@extends('layouts.pag')

@section('title', $contenido->titulo)

@section('content')
    <x-flecha-atras />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">

        {{-- Contenido --}}
        <div class="p-6 bg-tarjeta border border-slate-700 rounded-xl shadow-lg">

            
    <div class="flex items-center justify-center gap-3 mb-6">
        <h1 class="text-3xl font-bold text-center">{{ $contenido->titulo }}</h1>

        @if ($actividad && $actividad->favorito == 1)
            <x-corazon-relleno class="w-7 h-7 text-red-500" />
        @endif
    </div>

            @if (isset($contenido->detalles['imagen']))
                <div class="flex justify-center mb-6">
                    <img src="{{ $contenido->detalles['imagen'] }}" alt="{{ $contenido->titulo }}"
                        class="w-64 h-auto rounded-lg shadow">
                </div>
            @endif

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Descripción</h2>
                <p class="text-slate-300 leading-relaxed">
                    {{ $contenido->sinopsis ?? 'Sin descripción disponible.' }}
                </p>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Tipo</h2>
                <p class="text-slate-300">
                    {{ $contenido->tipo }}
                </p>
            </div>

            <form action="{{ route('contenido.destroy', $contenido->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar este contenido?')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow">
                    Eliminar
                </button>
            </form>

        </div>

        {{-- Actividad --}}
        <div class="p-6 bg-tarjeta border border-slate-700 rounded-xl shadow-lg">

            <h2 class="text-2xl font-bold mb-6 text-center">Tu actividad</h2>

            @if ($actividad)
                <div class="space-y-4">

                    <p class="text-slate-300">
                        <span class="font-semibold">Estado:</span>
                        {{ $actividad->estado }}
                    </p>

                    <p class="text-slate-300">
                        <span class="font-semibold">Fecha:</span>
                        {{ $actividad->created_at->format('d/m/Y H:i') }}
                    </p>

                    <p class="text-slate-300 flex items-center gap-2">
                        <span class="font-semibold">Puntuación:</span>

                        @php
                            $valor = $actividad->valoracion ?? 0;
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $valor)
                                <x-estrella-rellena class="text-yellow-400 w-6 h-6" />
                            @else
                                <x-estrella-vacia class="text-yellow-400 w-6 h-6" />
                            @endif
                        @endfor
                    </p>



                    <p class="text-slate-300">
                        <span class="font-semibold">Reseña:</span>
                        {{ $actividad->comentario ?? 'No hay reseña escrita.' }}
                    </p>

                </div>
            @else
                <p class="text-slate-400 italic text-center">
                    No hay actividad registrada para este contenido.
                </p>
            @endif
            <a href="{{ route('actividades.edit', $actividad->id) }}"
   class="inline-block mt-4 px-4 py-2 bg-colorInputs text-white font-semibold rounded-lg hover:bg-[#C30B4E]">
    Editar actividad
</a>


        </div>

    </div>
@endsection
