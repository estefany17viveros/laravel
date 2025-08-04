<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity_available',
        'product_id',
        'minimum_stock', // Añadido campo hipotético para ejemplo
        'location' // Añadido campo hipotético para ejemplo
    ];

    // Configuración para consultas
    protected $allowFilter = [
        'id',
        'quantity_available',
        'product_id',
        'minimum_stock',
        'location'
    ];
    
    protected $allowSort = [
        'id',
        'quantity_available',
        'product_id',
        'created_at'
    ];
    
    protected $allowIncluded = [
        'product',
        'product.category' // Permitir relación anidada
    ];

    // Relaciones
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes optimizados
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return $query;
        }

        $relations = explode(',', request('included'));

        $filtered = array_filter($relations, function ($relation) {
            $root = explode('.', $relation)[0];
            return in_array($root, $this->allowIncluded);
        });

        return $query->with($filtered);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');

        foreach ($filters as $column => $value) {
            if (in_array($column, $this->allowFilter)) {
                // Manejo especial para búsquedas exactas en IDs
                if (str_ends_with($column, '_id')) {
                    $query->where($column, $value);
                } else {
                    $query->where($column, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));

        foreach ($sortFields as $field) {
            $direction = 'asc';
            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = substr($field, 1);
            }

            if (in_array($field, $this->allowSort)) {
                $query->orderBy($field, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            if ($perPage > 0) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}