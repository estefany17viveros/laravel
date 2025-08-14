<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'name',             
        'email',           
        'password',          
        'role_id',           
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $allowIncluded = [
        'role',
        'pets',
        'orders',
        'shoppingCarts',
        'forums',
        'requests',
        'payments',
        'notifications',
        'trainer',
        'veterinary',
        'shelter'
    ];

    protected $allowFilter = [
        'id',
        'name',
        'email',
        'role.name',
        'pets.name',
        'orders.status',
        'forums.title'
    ];

    protected $allowSort = [
        'id',
        'name',
        'email',
        'created_at',
        'role.name'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shoppingCarts()
    {
        return $this->hasMany(ShoppingCart::class);
    }

    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class);
    }

    public function veterinary()
    {
        return $this->hasOne(Veterinary::class);
    }

    public function shelter()
    {
        return $this->hasOne(Shelter::class);
    }

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return $query;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        $validRelations = array_filter($relations, fn($relation) => $allowIncluded->contains($relation));

        if (!empty($validRelations)) {
            $query->with($validRelations);
        }

        return $query;
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
                    $query->where($column, 'LIKE', "%$value%");
                }
            }
        }

        return $query;
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

        return $query;
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        return request('perPage') ? $query->paginate(request('perPage')) : $query->get();
    }
}