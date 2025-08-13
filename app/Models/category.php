<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    /**
     * Atributos asignables
     */
    protected $fillable = [
        'name',       
        'description'   
    ];

    /**
     * Campos permitidos para filtrado
     */
    protected $allowedFilters = [
        'id',
        'name',
        'description'
    ];

    /**
     * Campos permitidos para ordenamiento
     */
    protected $allowedSorts = [
        'id',
        'name',
        'created_at'
    ];

    /**
     * Relaciones permitidas para inclusión
     */
    protected $allowedIncludes = [
        'products'
    ];

    /**
     * Relación con PRODUCTOS (1:N)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
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
     * Scope para paginación
     */
    public function scopeGetOrPaginate(Builder $query)
    {
        if (request()->has('per_page')) {
            $perPage = intval(request('per_page'));
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}