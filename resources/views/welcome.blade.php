<x-app-layout>
    <div class="flex flex-wrap justify-center gap-8 p-4">
        <div class="card bg-base-100 w-96">
            <figure class="px-10 pt-10">
                <div class="radial-progress"
                    style="--value:{{ $ciudadesProgress ?? 0 }}; --size:12rem; --thickness: 2rem;" aria-valuenow="70"
                    role="progressbar">
                    {{ $ciudadesProgress ?? 0 }}%</div>
            </figure>
            <div class="card-body items-center text-center">
                <h2 class="card-title">Ciudades</h2>
                <p>{{ $totalCiudades ?? 0 }} ciudades registradas</p>

            </div>
        </div>

        <div class="card bg-base-100 w-96">
            <figure class="px-10 pt-10">
                <div class="radial-progress" style="--value:{{ $rutasProgress ?? 0 }}; --size:12rem; --thickness: 2rem;"
                    aria-valuenow="70" role="progressbar">
                    {{ $rutasProgress ?? 0 }}%</div>
            </figure>
            <div class="card-body items-center text-center">
                <h2 class="card-title">Rutas</h2>
                <p>{{ $totalRutas ?? 0 }} ciudades registradas</p>
            </div>
        </div>
    </div>
</x-app-layout>
