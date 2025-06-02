<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-100">Gestión de Ciudades</h1>
            <button onclick="document.getElementById('addCityModal').showModal()" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Agregar Ciudad
            </button>
        </div>

        <!-- Modal para agregar ciudad -->
        <dialog id="addCityModal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Agregar Nueva Ciudad</h3>
                <form action="{{ route('ciudades.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nombre de la Ciudad</span>
                        </label>
                        <input type="text" name="nombre" class="input input-bordered w-full" required>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" onclick="document.getElementById('addCityModal').close()"
                            class="btn">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Tabla de ciudades -->
        <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
            <table class="table">
                <thead>
                    <tr class="text-gray-100">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ciudades as $ciudad)
                        <tr>
                            <td>{{ $ciudad->id }}</td>
                            <td>{{ $ciudad->nombre }}</td>
                            <td>
                                @if ($ciudad->esta_activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-error">Inactivo</span>
                                @endif
                            </td>
                            <td class="flex space-x-2">
                                @if ($ciudad->esta_activo)
                                    <form action="{{ route('ciudades.desactivate', $ciudad) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-error btn-sm"
                                            onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                            <i class="fas fa-ban"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
