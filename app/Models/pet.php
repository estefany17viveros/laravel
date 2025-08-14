<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'species',
        'breed',
        'size', 
        'sex',
        'description', 
        'photo',
        'trainer_id', 
        'shelter_id', 
        'user_id',
        'veterinary_id', 
    ];

    protected $allowIncluded = ['trainer', 'refuge', 'user', 'veterinary', 'adoptions'];
    protected $allowFilter = ['id', 'name', 'species', 'breed', 'sex', 'trainer.name', 'refuge.name', 'user.name', 'veterinary.name'];
    protected $allowSort = ['id', 'name', 'age', 'size', 'trainer.name', 'refuge.name', 'user.name', 'veterinary.name'];

    // Relaciones
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function refuge()
    {
        return $this->belongsTo(Refuge::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function veterinary()
    {
        return $this->belongsTo(Veterinary::class);
    }

    public function adoptions()
    {
        return $this->hasMany(Adoption::class, 'mascota_ID');
    }

    // Scopes
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
                    $query->where($column, 'LIKE', "%$value%");
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
        return request('perPage') ? $query->paginate(request('perPage')) : $query->get();
    }
}