<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'user_id',
    ];

    protected $allowFilter = ['name', 'user_id'];
    protected $allowSort = ['id', 'name', 'date'];
    protected $allowIncluded = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) return;

        $relations = explode(',', request('included'));
        $relations = collect($relations)->filter(fn($relation) => in_array($relation, $this->allowIncluded));
        $query->with($relations->toArray());
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) return;

        foreach (request('filter') as $column => $value) {
            if (in_array($column, $this->allowFilter)) {
                $query->where($column, 'like', "%$value%");
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) return;

        foreach (explode(',', request('sort')) as $sortField) {
            $direction = str_starts_with($sortField, '-') ? 'desc' : 'asc';
            $column = ltrim($sortField, '-');

            if (in_array($column, $this->allowSort)) {
                $query->orderBy($column, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        return request('perPage') ? $query->paginate(intval(request('perPage'))) : $query->get();
    }
}
