<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class TipoPago extends Model
{
    use HasFactory;

    protected $table = 'tipos_pago';

  
    protected $fillable = [
        'amount',             
        'method',
        'status',       
        'payable_type',      // para relación polimórfica
        'payable_id'         // para relación polimórfica
    ];

    /**
     * Campos permitidos para filtrado
     */
    protected $allowedFilters = [
        'id',
        'monto',
        'metodo_pago',
        'pagable_type'
    ];

    /**
     * Campos permitidos para ordenamiento
     */
    protected $allowedSorts = [
        'id',
        'monto',
        'created_at'
    ];

    /**
     * Relaciones permitidas para inclusión
     */
    protected $allowedIncludes = [
        'pagable',          // Relación polimórfica
        'metodoPago'        // Relación con método de pago
    ];

    /**
     * Relación polimórfica (Pedido/Veterinaria/Entrenador)
     */
    public function pagable()
    {
        return $this->morphTo();
    }

    /**
     * Relación con MÉTODO_PAGO (1:1)
     */
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pagos_id');
    }

    /**
     * Scope para incluir relaciones
     */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowedIncludes) || !request()->has('include')) {
            return $query;
        }

        $relations = explode(',', request('include'));

        $validIncludes = collect($relations)->filter(function ($relation) {
            return in_array($relation, $this->allowedIncludes);
        })->toArray();

        return $query->with($validIncludes);
    }

    /**
     * Scope para filtrar
     */
    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowedFilters) || !request()->has('filter')) {
            return $query;
        }

        $filters = request('filter');

        foreach ($filters as $filter => $value) {
            if (in_array($filter, $this->allowedFilters)) {
                $query->where($filter, 'LIKE', "%{$value}%");
            }
        }

        return $query;
    }

    /**
     * Scope para ordenar
     */
    public function scopeSort(Builder $query)
    {
        if (empty($this->allowedSorts) || !request()->has('sort')) {
            return $query;
        }

        $sortFields = explode(',', request('sort'));

        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            
            if (str_starts_with($sortField, '-')) {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if (in_array($sortField, $this->allowedSorts)) {
                $query->orderBy($sortField, $direction);
            }
        }

        return $query;
    }

    /**
     * Casts para los atributos
     */
    protected $casts = [
        'monto' => 'decimal:2'
    ];

    /**
     * Obtener el tipo de entidad pagable
     */
    public function getTipoPagableAttribute(): string
    {
        return match($this->pagable_type) {
            'App\Models\Pedido' => 'pedido',
            'App\Models\Veterinaria' => 'veterinaria',
            'App\Models\Entrenador' => 'entrenador',
            default => 'desconocido'
        };
    }
}