<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'creation_date',
        'user_id',
        'forum_id',
        'status',
        'views_count',
        'last_activity'
    ];

    protected $allowIncluded = [
        'user',
        'forum',
        'forum.category',
        'posts',
        'posts.user',
        'tags'
    ];

    protected $allowFilter = [
        'id',
        'title',
        'creation_date',
        'status',
        'views_count',
        'user.id',
        'user.name',
        'user.email',
        'forum.id',
        'forum.name',
        'forum.category.name',
    ];

    protected $allowSort = [
        'id',
        'title',
        'creation_date',
        'status',
        'views_count',
        'last_activity',
        'user.name',
        'forum.name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'topic_tags', 
            'id_topics',        
            'id_tags'    
        );
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
                    // Manejo especial para campos numéricos y fechas
                    if (in_array($column, ['views_count'])) {
                        $query->where($column, $value);
                    } elseif (in_array($column, ['creation_date', 'last_activity'])) {
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