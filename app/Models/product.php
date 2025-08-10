<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'veterinary_id',
        'shoppingcar_id',
    ];

    //  Relaciones
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function veterinary()
    {
        return $this->belongsTo(Veterinary::class);
    }

    public function shoppingcar()
    {
        return $this->belongsTo(Shoppingcar::class);
    }

    public function orderitems()
    {
        return $this->hasMany(Orderitem::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    protected $allowIncluded = [
        'category',
        'veterinary',
        'shoppingcar',
        'orderitems',
        'inventories'
    ];

    protected $allowFilter = [
        'name',
        'description',
        'price'
    ];

    protected $allowSort = [
        'name',
        'price',
        'created_at'
    ];

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));

        foreach ($relations as $key => $relation) {
            if (!in_array($relation, $this->allowIncluded)) {
                unset($relations[$key]);
            }
        }

        if (!empty($relations)) {
            $query->with($relations);
        }
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');

        foreach ($filters as $column => $value) {
            if (in_array($column, $this->allowFilter)) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }

    public function scopeSort(Builder $query)
    {
        if (request()->has('sort_by') && request()->has('sort_direction')) {
            $column = request('sort_by');
            $direction = request('sort_direction');

            if (in_array($column, $this->allowSort)) {
                return $query->orderBy($column, $direction);
            }
        }
    }
}
