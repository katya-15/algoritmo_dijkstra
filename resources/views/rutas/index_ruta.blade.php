<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-100">Gestión de Rutas</h1>
            <div class="flex space-x-4">
                <button onclick="document.getElementById('addRouteModal').showModal()" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Agregar Ruta
                </button>
                <button onclick="document.getElementById('findRouteModal').showModal()" class="btn btn-secondary">
                    <i class="fas fa-route mr-2"></i> Buscar Rutas
                </button>
            </div>
        </div>

        <!-- Modal para agregar ruta -->
        <dialog id="addRouteModal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Agregar Nueva Ruta</h3>
                <form action="{{ route('rutas.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Ciudad Origen</span>
                            </label>
                            <select name="ciudad_origen_id" class="select select-bordered w-full" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Ciudad Destino</span>
                            </label>
                            <select name="ciudad_destino_id" class="select select-bordered w-full" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Distancia (km)</span>
                            </label>
                            <input type="number" name="distancia" class="input input-bordered w-full" required
                                min="1">
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Ruta Activa</span>
                                <input type="checkbox" name="activo" class="toggle toggle-primary" checked>
                            </label>
                        </div>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" onclick="document.getElementById('addRouteModal').close()"
                            class="btn">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Modal para editar ruta -->
        <dialog id="editRouteModal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Editar Ruta</h3>
                <form id="editRouteForm" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Ciudad Origen</span>
                            </label>
                            <select name="ciudad_origen_id" id="edit_ciudad_origen_id" class="select select-bordered w-full" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Ciudad Destino</span>
                            </label>
                            <select name="ciudad_destino_id" id="edit_ciudad_destino_id" class="select select-bordered w-full" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Distancia (km)</span>
                            </label>
                            <input type="number" name="distancia" id="edit_distancia" class="input input-bordered w-full" required min="1">
                        </div>
                        
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" onclick="document.getElementById('editRouteModal').close()"
                            class="btn">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Modal para buscar rutas -->
        <dialog id="findRouteModal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Buscar Rutas</h3>
                <form action="{{ route('rutas.encontrar') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Ciudad Origen</span>
                            </label>
                            <select name="origen" class="select select-bordered w-full" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Ciudad Destino</span>
                            </label>
                            <select name="destino" class="select select-bordered w-full" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn btn-secondary">Buscar</button>
                        <button type="button" onclick="document.getElementById('findRouteModal').close()"
                            class="btn">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Tabla de rutas -->
        <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
            <table class="table">
                <thead>
                    <tr class="text-gray-100">
                        <th>ID</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Distancia (km)</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rutas as $ruta)
                        <tr>
                            <td>{{ $ruta->id }}</td>
                            <td>{{ $ruta->origenCity->nombre }}</td>
                            <td>{{ $ruta->destinoCity->nombre }}</td>
                            <td>{{ $ruta->distancia }}</td>
                            <td>
                                @if ($ruta->activo)
                                    <span class="badge badge-success">Activa</span>
                                @else
                                    <span class="badge badge-error">Inactiva</span>
                                @endif
                            </td>
                            <td class="flex space-x-2">
                                <button onclick="openEditModal({{ $ruta }})" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                
                                <form action="{{ route('rutas.updateStatus', $ruta) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $ruta->activo ? 'btn-warning' : 'btn-success' }}">
                                        {{ $ruta->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                @if ($ruta->esta_activo)
                                    <form action="{{ route('rutas.desactivate', $ruta->id) }}" method="POST">
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

    <script>
        function openEditModal(ruta) {
            // Llenar el formulario con los datos de la ruta
            document.getElementById('edit_ciudad_origen_id').value = ruta.ciudad_origen_id;
            document.getElementById('edit_ciudad_destino_id').value = ruta.ciudad_destino_id;
            document.getElementById('edit_distancia').value = ruta.distancia;
            
            // Configurar la acción del formulario
            document.getElementById('editRouteForm').action = `/rutas/${ruta.id}`;
            
            // Mostrar el modal
            document.getElementById('editRouteModal').showModal();
        }
    </script>
</x-app-layout>