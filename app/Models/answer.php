<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    /**
     * Atributos asignables
     */
    protected $fillable = [
        'content', // Campo del diagrama original
        'creation_date', // Campo del diagrama original
        'topic_id', // Relación con temas
    ];

    /**
     * Campos permitidos para filtrado
     */
    protected $allowedFilters = [
        'id',
        'Content',
        'creation_date',
        'topic_id'
    ];

    /**
     * Campos permitidos para ordenamiento
     */
    protected $allowedSorts = [
        'id',
        'creation_date',
        'created_at'
    ];

    /**
     * Relaciones permitidas para inclusión
     */
    protected $allowedIncludes = [
        'topic',
        'socks' // Relación adicional del diagrama
    ];

   
    public function topics()
    {
        return $this->belongsTo(Topic::class); // Usando claves del diagrama
    }

   
    public function users()
    {
        return $this->belongsTo(User::class);
    }

  
    public function socks()
    {
        return $this->hasMany(Sock::class);
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

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request()->has('per_page')) {
            $perPage = intval(request('per_page'));
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}