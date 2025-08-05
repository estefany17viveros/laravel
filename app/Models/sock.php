<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Sock extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'URL', 
        'Upload_Date',
        'topic_id',
        'size',
        'color',
        'description'
    ];

    protected $allowIncluded = ['topic', 'topic.category', 'tags'];
    protected $allowFilter = [
        'id',
        'type',
        'Upload_Date',
        'size',
        'color',
        'topic.id',
        'topic.name',
        'topic.category.name',
        'tags.name'
    ];
    protected $allowSort = [
        'id',
        'type',
        'Upload_Date',
        'size',
        'topic.name',
        'topic.created_at'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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
                    
                    if ($relation === 'tags') {
                        $query->whereHas($relation, function($q) use ($field, $value) {
                            $q->where($field, 'LIKE', "%$value%");
                        });
                    } else {
                        $query->whereHas($relation, function($q) use ($field, $value) {
                            $q->where($field, 'LIKE', "%$value%");
                        });
                    }
                } else {
                    // Manejo especial para campos de fecha
                    if ($column === 'Upload_Date') {
                        $query->whereDate($column, $value);
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