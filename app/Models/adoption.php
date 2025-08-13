<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Adoption extends Model
{
    use HasFactory;

    protected $table = 'adoptions';

    
    protected $fillable = [
        'status',
        'comments',
        'pet_id',
        'shelter_id'
    ];

  
    protected $allowedFilters = [
        'status',
        'pet_id',
        'shelter_id',
        'created_at'
    ];

   
    protected $allowedSorts = [
        'status',
        'created_at'
    ];

    
    protected $allowedIncludes = [
        'pet',
        'shelter',
        'requests'
    ];

   
    public function pets()
    {
        return $this->belongsTo(Pet::class);
    }
    
    public function shelters()
    {
        return $this->belongsTo(Shelter::class);
    }

    
    public function requests()
    {
        return $this->hasMany(Requestt::class);
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