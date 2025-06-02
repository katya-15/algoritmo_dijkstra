<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{

    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'nombre',
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

    // Método para desactivar la ciudad
    public function desactivate(): bool
    {
        try {
            $this->status_id = 2;
            return $this->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }

    public function origenRoutes()
    {
        return $this->hasMany(Route::class, 'ciudad_origen_id');
    }

    public function destinoRoutes()
    {
        return $this->hasMany(Route::class, 'ciudad_destino_id');
    }
}
