<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    //
    use HasFactory;

    protected $table = 'routes';

    protected $fillable = [
        'ciudad_origen_id',
        'ciudad_destino_id',
        'distancia',
        'activo',
        'status_id'
    ];

    protected $appends = ['esta_activo'];
    
    // Relación con Status
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    // Accesor para verificar si está activo
    public function getEstaActivoAttribute(): bool
    {
        return $this->status_id === Status::ACTIVE;
    }

    // Método para desactivar la ruta
    public function desactivate(): bool
    {
        try {
            $this->status_id = 2;
            return $this->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    // Scope para rutas activas
    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }

    public function origenCity()
    {
        return $this->belongsTo(City::class, 'ciudad_origen_id');
    }

    public function destinoCity()
    {
        return $this->belongsTo(City::class, 'ciudad_destino_id');
    }
}
