@extends('layouts.pag')

@section('title', 'Editar actividad')

@section('content')
    <style>
.rating label:has(input:checked) .estrella-rellena {
    opacity: 1;
}

.rating label:has(input:checked) .estrella-vacia {
    opacity: 0;
}

.rating label:has(input:checked) ~ label .estrella-rellena {
    opacity: 1;
}

.rating label:has(input:checked) ~ label .estrella-vacia {
    opacity: 0;
}
    </style>
    <x-flecha-atras />

    <div class="max-w-xl mx-auto mt-10 p-6 bg-tarjeta border border-slate-700 rounded-xl shadow-lg">

        <h1 class="text-2xl font-bold mb-6 text-center">Editar actividad</h1>

        <form action="{{ route('actividades.update', $actividad->id) }}" method="POST">
            @csrf
            @method('PUT')

           <div class="flex items-start justify-between mb-4 gap-6">

    {{-- Estado --}}
    <div>
        <label class="text-slate-300 font-semibold block mb-1">Estado</label>
        <select name="estado" class="w-32 bg-gray-800 text-white px-4 py-2 rounded-lg shadow cursor-pointer">
            <option value="no_visto" {{ $actividad->estado === 'no_visto' ? 'selected' : '' }}>No visto</option>
            <option value="visto" {{ $actividad->estado === 'visto' ? 'selected' : '' }}>Visto</option>
            <option value="viendo" {{ $actividad->estado === 'viendo' ? 'selected' : '' }}>Viendo</option>
        </select>
    </div>

    {{-- Valoración --}}
    <div>
        <label class="text-slate-300 font-semibold block mb-1">Valoración</label>

        <div class="rating flex flex-row-reverse gap-1">
    @for ($i = 5; $i >= 1; $i--)
        <label class="cursor-pointer relative">
            <input type="radio" name="valoracion" value="{{ $i }}" class="hidden peer"
                {{ $actividad->valoracion == $i ? 'checked' : '' }}>    

            <span class="estrella-vacia block">
                <x-estrella-vacia />
            </span>

            <span class="estrella-rellena absolute inset-0 opacity-0 peer-checked:opacity-100">
                <x-estrella-rellena />
            </span>
        </label>
    @endfor
</div>

    </div>

</div>




            {{-- Comentario --}}
            <div class="mb-4">
                <label class="text-slate-300 font-semibold">Comentario</label>
                <textarea name="comentario" rows="3" class="w-full bg-slate-800 text-white rounded-md mt-1">{{ old('comentario', $actividad->comentario) }}</textarea>
            </div>

            {{-- Favorito --}}
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" name="favorito" value="1"
                    {{ old('favorito', $actividad->favorito) ? 'checked' : 0 }}>
                <label class="text-slate-300 font-semibold">Marcar como favorito</label>
            </div>

            <div class="flex justify-between mt-6">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Guardar cambios
                </button>

                <a href="{{ route('contenido.mios.show', $actividad->id) }}"
                    class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
@endsection





