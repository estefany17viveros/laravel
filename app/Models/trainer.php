<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'specialty', 'experience', 'rating',
        'phone', 'email', 'biography', 'user_id'
    ];

    protected $allowIncluded = ['user', 'appointments', 'services'];
    protected $allowFilter = ['id', 'name', 'specialty', 'email'];
    protected $allowSort = ['id', 'name', 'rating', 'experience'];

    // Relaciones
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

    // Scopes
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));
        $allowed = collect($this->allowIncluded);

        foreach ($relations as $key => $relation) {
            if (!$allowed->contains($relation)) {
                unset($relations[$key]);
            }
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowed = collect($this->allowFilter);

        foreach ($filters as $column => $value) {
            if ($allowed->contains($column)) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $fields = explode(',', request('sort'));
        $allowed = collect($this->allowSort);

        foreach ($fields as $field) {
            $direction = 'asc';
            if (substr($field, 0, 1) == '-') {
                $direction = 'desc';
                $field = substr($field, 1);
            }

            if ($allowed->contains($field)) {
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
