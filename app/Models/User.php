<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_id',
    ];

  
    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    
    public function shoppingcart()
    {
        return $this->hasOne(Shoppingcar::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function requestts()
    {
        return $this->hasMany(Requestt::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
     public function roles()
    {
        return $this->morphMany(Role::class, 'roleable');
    }

    

    public function isAdmin() { return $this->role === 'admin'; }
    public function isSubadmin() { return $this->role === 'subadmin'; }
    public function isCustomer() { return $this->role === 'customer'; }

   
    protected function getAllowIncluded()
    {
        return [
            'forums',
            'forums.comments',
            'forums.comments.user',
            'orders',
            'shoppingcart',
            'notifications',
            'requestts',
            'pets',
            'pets.type', 
            'payments',
            'profile',
        ];
    }

  
    public function scopeIncluded(Builder $query)
    {
        $allowIncluded = $this->getAllowIncluded();

        if (empty($allowIncluded) || !request()->has('included')) {
            return $query;
        }

        $relations = explode(',', request('included'));

        $validRelations = array_filter($relations, function ($relation) use ($allowIncluded) {
            return in_array($relation, $allowIncluded);
        });

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

        foreach (request('filter') as $column => $value) {
            if (in_array($column, $this->allowFilter)) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        return $query;
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request()->has('perPage')) {
            $perPage = intval(request('perPage'));
            return $perPage ? $query->paginate($perPage) : $query->get();
        }
        return $query->get();
    }

    public function scopeSort(Builder $query)
    {
        if (request()->has('sort_by') && request()->has('sort_direction')) {
            $column = request('sort_by');
            $direction = request('sort_direction');

            $allowed = ['name', 'email', 'created_at'];
            if (in_array($column, $allowed)) {
                $query->orderBy($column, $direction);
            }
        }
        return $query;
    }

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
