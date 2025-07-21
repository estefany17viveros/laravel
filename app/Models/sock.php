<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Sock extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'URL', 'Upload_Date', 'topic_id'];

    protected $allowIncluded = ['topic'];
    protected $allowFilter = ['id', 'type', 'Upload_Date'];
    protected $allowSort = ['id', 'type', 'Upload_Date'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
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
