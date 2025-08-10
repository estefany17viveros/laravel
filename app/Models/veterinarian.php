<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Veterinarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialty',
        'experience',
        'qualifications',
        'biography',
        'license_number',
        'consultation_fee',
        'availability',
        'user_id',
        'shelter_id'
    ];

    protected $allowIncluded = [
        'user', 
        'user.profile',
        'shelter',
        'shelter.location',
        'appointments',
        'appointments.pet',
        'pets',
        'pets.owner'
    ];

    protected $allowFilter = [
        'id',
        'name',
        'email',
        'phone',
        'specialty',
        'experience',
        'license_number',
        'consultation_fee',
        'availability',
        'user.name',
        'user.email',
        'shelter.name',
        'shelter.city',
        'appointments.status',
        'appointments.date',
        'pets.species'
    ];

    protected $allowSort = [
        'id',
        'name',
        'email',
        'specialty',
        'experience',
        'consultation_fee',
        'user.created_at',
        'shelter.name',
        'appointments.date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
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
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column);
                    $query->whereHas($relation, function($q) use ($field, $value) {
                        if ($relation === 'appointments' && $field === 'date') {
                            $q->whereDate($field, $value);
                        } else {
                            $q->where($field, 'LIKE', "%$value%");
                        }
                    });
                } else {
                    if (in_array($column, ['consultation_fee', 'experience'])) {
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