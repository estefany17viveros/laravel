<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_address',
        'cost',
        'status',
        'shipping_method',
        'order_id',
        'tracking_number',
        'estimated_delivery',
        'shipped_at',
    ];

    protected $allowIncluded = ['order', 'order.customer', 'order.items'];
    protected $allowFilter = [
        'id',
        'shipping_address',
        'cost',
        'status',
        'shipping_method',
        'tracking_number',
        'estimated_delivery',
        'shipped_at',
        'order.id',
        'order.total_amount',
        'order.status',
        'order.customer.name',
        'order.customer.email'
    ];
    protected $allowSort = [
        'id',
        'cost',
        'status',
        'shipping_method',
        'estimated_delivery',
        'shipped_at',
        'order.created_at',
        'order.total_amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
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
                    $query->whereHas($relation, function($q) use ($field, $value) {
                        $q->where($field, 'LIKE', "%$value%");
                    });
                } else {
                    // Manejo especial para campos numéricos y fechas
                    if (in_array($column, ['cost', 'order.total_amount'])) {
                        $query->where($column, $value);
                    } elseif (in_array($column, ['estimated_delivery', 'shipped_at'])) {
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