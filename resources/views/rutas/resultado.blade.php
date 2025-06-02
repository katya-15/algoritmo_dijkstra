<x-app-layout>
    <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-100">
            Rutas de <span class="text-primary">{{ $origen }}</span> a <span class="text-secondary">{{ $destino }}</span>
        </h1>
        <a href="{{ route('rutas.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <!-- Ruta más corta -->
    <div class="mb-10">
        <div class="flex items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-100">Ruta más corta</h2>
            <span class="ml-4 badge badge-primary">
                {{ $rutaCorta['distancia'] }} km
            </span>
        </div>
        
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <div class="steps steps-horizontal">
                    @foreach($rutaCorta['path'] as $ciudad)
                    <div class="step {{ $loop->first || $loop->last ? 'step-primary' : '' }}">
                        <div class="step-content">
                            <div class="font-bold">{{ $ciudad }}</div>
                            @if(!$loop->last)
                            <div class="text-xs opacity-50 mt-1">
                                {{ $rutaCorta['distancia'] / (count($rutaCorta['path']) - 1) }} km
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Rutas alternativas -->
    @if(count($rutasAlternativas) > 0)
    <div>
        <h2 class="text-2xl font-semibold text-gray-100 mb-4">Rutas alternativas</h2>
        
        <div class="grid gap-6">
            @foreach($rutasAlternativas as $ruta)
            <div class="card bg-base-200 shadow-xl">
                <div class="card-body">
                    <div class="flex items-center mb-2">
                        <h3 class="text-lg font-semibold">Opción {{ $loop->iteration }}</h3>
                        <span class="ml-4 badge badge-secondary">
                            {{ $ruta['distancia'] }} km
                        </span>
                    </div>
                    
                    <div class="steps steps-horizontal">
                        @foreach($ruta['path'] as $ciudad)
                        <div class="step {{ $loop->first || $loop->last ? 'step-secondary' : '' }}">
                            <div class="step-content">
                                <div class="font-bold">{{ $ciudad }}</div>
                                @if(!$loop->last)
                                <div class="text-xs opacity-50 mt-1">
                                    {{ $ruta['distancia'] / (count($ruta['path']) - 1) }} km
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="alert alert-warning shadow-lg">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>No se encontraron rutas alternativas</span>
        </div>
    </div>
    @endif
</div>
</x-app-layout>