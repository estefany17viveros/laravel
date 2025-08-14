<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;
     
    protected $table ='payment_methods';

    protected $fillable = [
        'type', 
        'description', 
        'expiration_date', 
        'payment_id'
    ];

    protected $allowedIncludes = ['payment', 'user'];
    protected $allowedSorts = ['id', 'type', 'expiration_date', 'created_at'];
    protected $allowedFilters = [
        'id',
        'payment_id',
        'type',
        'expiration_date'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function scopeInclude(Builder $query, ?array $relations = null): Builder
    {
        if (empty($relations)) {
            return $query;
        }

        $validRelations = array_intersect($relations, $this->allowedIncludes);
        
        return $query->with($validRelations);
    }

   
    public function scopeSort(Builder $query, string $attribute, string $order = 'asc'): Builder
    {
        if (!in_array($attribute, $this->allowedSorts)) {
            return $query;
        }

        return $query->orderBy($attribute, $order);
    }

   
    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        foreach ($filters as $key => $value) {
            if ($value === null || !in_array($key, $this->allowedFilters)) {
                continue;
            }

            switch ($key) {
                case 'id':
                case 'payment_id':
                    $query->where($key, $value);
                    break;
                    
                case 'type':
                    $query->where('type', 'like', "%{$value}%");
                    break;
                    
                case 'expiration_date':
                    if (is_array($value)) {
                        if (isset($value['from'])) {
                            $query->whereDate('expiration_date', '>=', $value['from']);
                        }
                        if (isset($value['to'])) {
                            $query->whereDate('expiration_date', '<=', $value['to']);
                        }
                    } else {
                        $query->whereDate('expiration_date', $value);
                    }
                    break;
            }
        }
        
        return $query;
    }

    public function scopeGetOrPaginate(
        Builder $query, 
        bool $paginate = false, 
        int $perPage = 15
    ) {
        return $paginate 
            ? $query->paginate($perPage)->appends(request()->query())
            : $query->get();
    }
}