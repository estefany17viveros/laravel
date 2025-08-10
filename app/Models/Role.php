<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_role',
        'description',
        'roleable_id',
        'roleable_type'
    ];

    // Relación polimórfica
    public function roleable()
    {
        return $this->morphTo();
    }

    /**
     * ?included=relacion1,relacion2
     */
    public function scopeIncluded(Builder $query)
    {
        if (request()->filled('included')) {
            $relations = explode(',', request('included'));
            $query->with($relations);
        }
        return $query;
    }

    /**
     * ?filter[name_role]=Admin&filter[description]=perros
     */
    public function scopeFilter(Builder $query)
    {
        if (request()->has('filter') && is_array(request('filter'))) {
            foreach (request('filter') as $field => $value) {
                if (!empty($value) && in_array($field, ['name_role', 'description'])) {
                    $query->where($field, 'like', "%{$value}%");
                }
            }
        }
        return $query;
    }

    /**
     * ?sort_by=name_role&sort_direction=asc
     */
    public function scopeSort(Builder $query)
    {
        if (request()->filled('sort_by') && request()->filled('sort_direction')) {
            $column = request('sort_by');
            $direction = request('sort_direction');

            $allowed = ['name_role', 'description', 'created_at'];
            if (in_array($column, $allowed)) {
                $query->orderBy($column, $direction);
            }
        }
        return $query;
    }

    /**
     * ?per_page=10  o  ?per_page=all
     */
    public function scopeOrPaginate(Builder $query)
    {
        if (request()->has('per_page')) {
            if (request('per_page') === 'all') {
                return $query->get();
            }
            return $query->paginate(request('per_page', 15));
        }

        return $query->get();
    }
}
