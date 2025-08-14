<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaymentType extends Model
{
    use HasFactory;


    protected $table = 'payment_types';

    protected $fillable = [
        'type',
        'description',
    ];

    protected $allowedIncludes = ['payments'];
    protected $allowedFilters = [
        'id',
        'type',
        'description',
        'payments.amount'
    ];
    protected $allowedSorts = [
        'id',
        'type',
        'created_at'
    ];

    
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    
    public function scopeIncluded(Builder $query): Builder
    {
        if (empty($this->allowedIncludes) || !request()->has('included')) {
            return $query;
        }

        $relations = array_map('trim', explode(',', request('included')));
        $validRelations = array_intersect($relations, $this->allowedIncludes);

        return $query->with($validRelations);
    }

    /**
     * Scope para filtrar resultados
     */
    public function scopeFilter(Builder $query): Builder
    {
        if (empty($this->allowedFilters) || !request()->has('filter')) {
            return $query;
        }

        $filters = request('filter');
        if (!is_array($filters)) {
            return $query;
        }

        foreach ($filters as $filter => $value) {
            if (empty($value) || !in_array($filter, $this->allowedFilters)) {
                continue;
            }

            if (Str::contains($filter, '.')) {
                [$relation, $field] = explode('.', $filter);
                $query->whereHas($relation, function ($q) use ($field, $value) {
                    $q->where($field, 'LIKE', "%{$value}%");
                });
                continue;
            }

            $query->where($filter, 'LIKE', "%{$value}%");
        }

        return $query;
    }

    
    public function scopeSort(Builder $query): Builder
    {
        if (empty($this->allowedSorts) || !request()->has('sort')) {
            return $query;
        }

        $sortFields = explode(',', request('sort'));
        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            $field = $sortField;

            if (Str::startsWith($sortField, '-')) {
                $direction = 'desc';
                $field = substr($sortField, 1);
            }

            if (in_array($field, $this->allowedSorts)) {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request()->has('perPage')) {
            $perPage = (int) request('perPage', 15);
            return $query->paginate($perPage)->appends(request()->query());
        }

        return $query->get();
    }
}