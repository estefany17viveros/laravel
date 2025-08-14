<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitude extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 
        'priority', 
        'solicitation_status', 
        'user_id',
        'shelter_id',
        'adoption_id',
        'service_id', 
    ];

    protected $allowIncluded = ['user', 'shelter', 'adoption', 'service'];
    protected $allowFilter = [
        'id', 
        'priority', 
        'solicitation_status',
        'user.name',
        'user.email',
        'shelter.name',
        'adoption.pet.name',
        'service.name'
    ];
    protected $allowSort = [
        'id', 
        'priority', 
        'date',
        'user.name',
        'shelter.name',
        'adoption.date',
        'service.name'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function refuge()
    {
        return $this->belongsTo(Refuge::class, 'refugio_id');
    }

    public function adoption()
    {
        return $this->belongsTo(Adoption::class, 'adopcion_id');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'solicitud_id');
    }

    // Scopes (mantener los mismos métodos que ya tienes)
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