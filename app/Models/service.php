<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration',
        'description',
        'veterinarian_id',
        'trainer_id',
        'requestt_id',
    ];

    protected $allowIncluded = ['veterinarian', 'trainer', 'requestt'];
    protected $allowFilter = [
        'id', 
        'name', 
        'price', 
        'duration',
        'veterinarian.name',
        'veterinarian.specialty',
        'trainer.name',
        'trainer.specialization',
        'requestt.priority'
    ];
    protected $allowSort = [
        'id', 
        'name', 
        'price', 
        'duration',
        'veterinarian.name',
        'trainer.name',
        'requestt.date'
    ];

    public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function requestt()
    {
        return $this->belongsTo(Requestt::class);
    }

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) return;

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relation) {
            if (!$allowIncluded->contains($relation)) {
                unset($relations[$key]);
            }
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) return;

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $column => $value) {
            if ($allowFilter->contains($column)) {
                // Verificar si es un filtro anidado (relación.campo)
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column);
                    $query->whereHas($relation, function($q) use ($field, $value) {
                        $q->where($field, 'LIKE', "%$value%");
                    });
                } else {
                    // Manejo especial para campos numéricos y de fecha
                    if (in_array($column, ['price', 'duration'])) {
                        $query->where($column, $value);
                    } else {
                        $query->where($column, 'LIKE', "%$value%");
                    }
                }
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) return;

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $field) {
            $direction = 'asc';
            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = substr($field, 1);
            }

            if ($allowSort->contains($field)) {
                // Verificar si es un ordenamiento anidado (relación.campo)
                if (str_contains($field, '.')) {
                    [$relation, $relationField] = explode('.', $field);
                    $query->with([$relation => function($q) use ($relationField, $direction) {
                        $q->orderBy($relationField, $direction);
                    }]);
                } else {
                    $query->orderBy($field, $direction);
                }
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}