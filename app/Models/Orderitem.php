<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'orderitems'; 

    protected $fillable = [
        'quantity',     
        'unit_price',      
        'product_id',     
        'order_id'        
    ];

    protected $allowIncluded = [
        'product',
        'order',
        'product.category'
    ];

    protected $allowFilter = [
        'id',
        'quantity',
        'unit_price',
        'product.name',
        'product.price',
        'order.status'
    ];

    protected $allowSort = [
        'id',
        'quantity',
        'unit_price',
        'created_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class); 
    }

    public function order()
    {
        return $this->belongsTo(Order::class); 
    }

    public function scopeIncluded(Builder $query): Builder
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return $query;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relation) {
            if (!$allowIncluded->contains($relation)) {
                unset($relations[$key]);
            }
        }

        return $query->with($relations);
    }

    public function scopeFilter(Builder $query): Builder
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return $query;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $column => $value) {
            if ($allowFilter->contains($column)) {
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column);
                    $query->whereHas($relation, function($q) use ($field, $value) {
                        $q->where($field, 'LIKE', "%$value%");
                    });
                } else {
                    if (in_array($column, ['quantity', 'unit_price'])) {
                        $query->where($column, $value); // Búsqueda exacta para valores numéricos
                    } else {
                        $query->where($column, 'LIKE', "%$value%");
                    }
                }
            }
        }

        return $query;
    }

    public function scopeSort(Builder $query): Builder
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return $query;
        }

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