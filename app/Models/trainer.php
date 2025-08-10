<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'experience',
        'rating',
        'phone',
        'email',
        'biography',
        'user_id',
        'status',
        'certifications',
        'hourly_rate'
    ];

    protected $allowIncluded = [
        'user',
        'user.profile',
        'appointments',
        'appointments.pet',
        'services',
        'services.pets'
    ];

    protected $allowFilter = [
        'id',
        'name',
        'specialty',
        'email',
        'phone',
        'rating',
        'experience',
        'status',
        'hourly_rate',
        'user.name',
        'user.email',
        'user.status',
        'services.name',
        'services.price',
        'appointments.status',
        'appointments.date'
    ];

    protected $allowSort = [
        'id',
        'name',
        'rating',
        'experience',
        'hourly_rate',
        'user.created_at',
        'services.price',
        'appointments.date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
     public function roles()
    {
        return $this->morphMany(Role::class, 'roleable');
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
                // Filtrado anidado para relaciones
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column);
                    $query->whereHas($relation, function($q) use ($field, $value) {
                        // Manejo especial para fechas en citas
                        if ($relation === 'appointments' && $field === 'date') {
                            $q->whereDate($field, $value);
                        } else {
                            $q->where($field, 'LIKE', "%$value%");
                        }
                    });
                } else {
                    // Manejo especial para campos numéricos
                    if (in_array($column, ['rating', 'experience', 'hourly_rate'])) {
                        $query->where($column, '>=', $value);
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
                // Ordenamiento anidado para relaciones
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
        $perPage = request('perPage');
        return $perPage ? $query->paginate(intval($perPage)) : $query->get();
    }
}