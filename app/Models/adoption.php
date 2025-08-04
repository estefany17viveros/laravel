<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Adoption extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_date',
        'status',
        'comments',
        'user_id',
        'pet_id',
        'requestt_id',
        'shelter_id',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function requestt()
    {
        return $this->belongsTo(Requestt::class);
    }

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    // Configuración para consultas
    protected $allowFilter = ['application_date', 'status', 'user_id', 'pet_id'];
    protected $allowSort = ['application_date', 'status', 'created_at'];

    protected function getAllowIncluded()
    {
        return ['user', 'pet', 'requestt', 'shelter'];
    }

    // Scopes para consultas anidadas (igual que en OrderItem)
    public function scopeIncluded(Builder $query)
    {
        $allowIncluded = $this->getAllowIncluded();

        if (!request()->filled('included')) {
            return $query;
        }

        $relations = explode(',', request('included'));

        $filtered = array_filter($relations, function ($relation) use ($allowIncluded) {
            $root = explode('.', $relation)[0];
            return in_array($root, $allowIncluded);
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
                $query->where($column, 'LIKE', '%' . $value . '%');
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
            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}