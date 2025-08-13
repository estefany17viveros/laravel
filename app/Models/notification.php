<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

   
    protected $fillable = [
        'Title',        
        'Description',   
        'user_id',       
        'appointment_id'        
    ];

  
    protected $allowedFilters = [
        'id',
        'Title',
        'user_id',
        'appointment_id'
    ];

   
    protected $allowedSorts = [
        'id',
        'titulo',
        'created_at'
    ];

 
    protected $allowedIncludes = [
        'usuario',
        'cita'
    ];

 
    public function users()
    {
        return $this->belongsTo(User::class);
    }

 
    public function appointments()
    {
        return $this->belongsTo(Appointments::class);
    }

   
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