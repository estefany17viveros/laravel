<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pago extends Model
{
    protected $table = 'pagos';
    
    protected $fillable = [
        'user_id',
        'date',
        'cantidad',
        'status',
        'pagable_id',
        'pagable_type'
    ];
    
    protected $casts = [
        'date' => 'datetime',
        'cantidad' => 'decimal:2'
    ];
    
    // Relación polimórfica (para pedido, veterinaria, entrenador)
    public function pagable(): MorphTo
    {
        return $this->morphTo();
    }
    
    // Relación con usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relación con método de pago
    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }
    
    // Scopes
    public function scopeInclude(Builder $query, array $includes): Builder
    {
        // Incluir relaciones
        if (in_array('usuario', $includes)) {
            $query->with('usuario');
        }
        
        if (in_array('metodoPago', $includes)) {
            $query->with('metodoPago');
        }
        
        if (in_array('pagable', $includes)) {
            $query->with('pagable');
        }
        
        return $query;
    }
    
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            if ($value === null) continue;
            
            switch ($key) {
                case 'user_id':
                    $query->where('user_id', $value);
                    break;
                case 'status':
                    $query->where('status', $value);
                    break;
                case 'min_amount':
                    $query->where('cantidad', '>=', $value);
                    break;
                case 'max_amount':
                    $query->where('cantidad', '<=', $value);
                    break;
                case 'date_from':
                    $query->where('date', '>=', $value);
                    break;
                case 'date_to':
                    $query->where('date', '<=', $value);
                    break;
                case 'pagable_type':
                    $query->where('pagable_type', $value);
                    break;
            }
        }
        
        return $query;
    }
    
    public function scopeSort(Builder $query, string $sortBy, string $sortDirection = 'asc'): Builder
    {
        $validSorts = ['date', 'cantidad', 'status', 'created_at'];
        
        if (in_array($sortBy, $validSorts)) {
            return $query->orderBy($sortBy, $sortDirection);
        }
        
        return $query->orderBy('date', 'desc');
    }
    
    public function scopeOrPaginate(Builder $query, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }
}