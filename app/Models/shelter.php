<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Shelter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'responsible', 'email', 'address', 'user_id'];

    protected $allowIncluded = ['user'];
    protected $allowFilter = ['id', 'name', 'email'];
    protected $allowSort = ['id', 'name', 'email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) return;
        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);
        foreach ($relations as $key => $relation) {
            if (!$allowIncluded->contains($relation)) unset($relations[$key]);
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
                $query->where($column, 'LIKE', "%$value%");
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
                $query->orderBy($field, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        return request('perPage') ? $query->paginate(intval(request('perPage'))) : $query->get();
    }
}
