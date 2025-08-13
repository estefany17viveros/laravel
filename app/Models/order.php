<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

   
    protected $fillable = [
        'total',          
        'status',         
        'order_date',  
        'user_id'      
    ];

  
    protected $allowedFilters = [
        'id',
        'total',
        'status',
        'order_date',
        'user_id'
    ];

    protected $allowedSorts = [
        'id',
        'total',
        'order_date',
        'created_at'
    ];

    protected $allowedIncludes = [
        'user',
        'shipping',
        'order_items',
        'payments'
    ];

   
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function order_items(): HasMany
    {
        return $this->hasMany(Orderitem::class);
    }

    public function payment_types()
    {
        return $this->morphOne(Payment_Types::class);
    }

    public function scopeIncluded(Builder $query): Builder
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

    /**
     * Scope para filtrar
     */
    public function scopeFilter(Builder $query): Builder
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

    /**
     * Scope para ordenar
     */
    public function scopeSort(Builder $query): Builder
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

    /**
     * Scope para paginación
     */
    public function scopeGetOrPaginate(Builder $query)
    {
        if (request()->has('per_page')) {
            $perPage = intval(request('per_page'));
            return $query->paginate($perPage);
        }

        return $query->get();
    }

   
}