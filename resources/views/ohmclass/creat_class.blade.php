<x-app-layout>


    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-100">Gestión de Clases

        </h1>
        <div class="flex space-x-4">
            <button onclick="document.getElementById('addRouteModal').showModal()" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Agregar Clase
            </button>

        </div>
    </div>

    <dialog id="addRouteModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Agregar Nueva Clase</h3>
            <form action="{{ route('rutas.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">titulo</span>
                        </label>
                        <input class="input input-bordered w-full" type="text">
                        
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Ciudad Destino</span>
                        </label>
                        <input type="text" name="" id="" class="input input-bordered w-full">
                        
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Distancia (km)</span>
                        </label>
                        <input type="file" name="" id="" class="input input-bordered w-full">
                        
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
                        <select name="ciudad_origen_id" id="edit_ciudad_origen_id" class="select select-bordered w-full"
                            required>
                            <option value="">Seleccione una ciudad</option>

                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Ciudad Destino</span>
                        </label>
                        <select name="ciudad_destino_id" id="edit_ciudad_destino_id"
                            class="select select-bordered w-full" required>
                            <option value="">Seleccione una ciudad</option>

                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Distancia (km)</span>
                        </label>
                        <input type="number" name="distancia" id="edit_distancia" class="input input-bordered w-full"
                            required min="1">
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


    <div class="overflow-x-auto">
        <table class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th></th>
                    <th>Clases</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody>
                <!-- row 1 -->
                <tr class="bg-base-200">
                    <th>1</th>
                    <td>Leds</td>
                    <td class="flex space-x-2">
                        <button onclick="openEditModal" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </button>


                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                <i class="fas fa-ban"></i> Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
                <!-- row 2 -->
                <tr>
                    <th>2</th>
                    <td>Motores</td>
                    <td class="flex space-x-2">
                        <button onclick="openEditModal" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </button>


                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                <i class="fas fa-ban"></i> Eliminar
                            </button>
                        </form>

                    </td>

                </tr>
                <!-- row 3 -->
                <tr>
                    <th>3</th>
                    <td>Fotocelda</td>
                    <td class="flex space-x-2">
                        <button onclick="openEditModal" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </button>


                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                <i class="fas fa-ban"></i> Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
                <tr>
                    <th>4</th>
                    <td>Fotocelda</td>
                    <td class="flex space-x-2">
                        <button onclick="openEditModal" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </button>


                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                <i class="fas fa-ban"></i> Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
                </tr>
                <tr>
                    <th>5</th>
                    <td>Transsitores</td>
                    <td class="flex space-x-2">
                        <button onclick="openEditModal" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </button>


                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                <i class="fas fa-ban"></i> Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
                <tr>
                    <th>6</th>
                    <td>Diodos</td>
                    <td class="flex space-x-2">
                        <button onclick="openEditModal" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </button>


                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-sm"
                                onclick="return confirm('¿Estás seguro de desactivar esta ciudad?')">
                                <i class="fas fa-ban"></i> Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function openEditModal(id, nombre) {
            // Llenar el formulario con los datos
            document.getElementById('editRouteForm').action = `/rutas/${id}`;
            document.getElementById('edit_ciudad_origen_id').value = ''; // Aquí deberías poner el valor real
            document.getElementById('edit_ciudad_destino_id').value = ''; // Aquí deberías poner el valor real
            document.getElementById('edit_distancia').value = ''; // Aquí deberías poner el valor real

            // Mostrar el modal
            document.getElementById('editRouteModal').showModal();
        }
    </script>

</x-app-layout>
