<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $allowIncluded = [
        'user',
        'shipment',
        'order_items',
        'payment'
    ];

    protected $allowFilter = [
        'id',
        'total',
        'status',
        'order_date',
        'user.name',
        'user.email',
        'shipment.status'
    ];

    protected $allowSort = [
        'id',
        'total',
        'order_date',
        'status',
        'user.created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class); 
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class); 
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeIncluded(Builder $query)
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

        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
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
                    if ($column === 'order_date') {
                        $query->whereDate($column, $value);
                    } elseif ($column === 'total') {
                        $query->where($column, $value); // Búsqueda exacta para total
                    } else {
                        $query->where($column, 'LIKE', "%$value%");
                    }
                }
            }
        }
    }

    public function scopeSort(Builder $query)
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
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        return request('perPage') ? $query->paginate(request('perPage')) : $query->get();
    }
}