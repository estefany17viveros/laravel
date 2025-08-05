<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date',
        'status',
        'payable_id',
        'payable_type',
        'user_id',
        'payment_method_id',
        'transaction_id' // Campo añadido
    ];

    // Configuración para consultas
    protected $allowFilter = [
        'id',
        'amount',
        'date',
        'status',
        'user_id',
        'payment_method_id',
        'transaction_id',
        'created_at'
    ];
    
    protected $allowSort = [
        'id',
        'amount',
        'date',
        'created_at'
    ];
    
    protected $allowIncluded = [
        'payable',
        'user',
        'paymentMethod',
        'payable.order' // Relación anidada hipotética
    ];

    
    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    
    public function payable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

   
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return $query;
        }

        $relations = explode(',', request('included'));

        $filtered = array_filter($relations, function ($relation) {
            $root = explode('.', $relation)[0];
            return in_array($root, $this->allowIncluded);
        });

        return $query->with($filtered);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');

        foreach ($filters as $column => $value) {
            if (in_array($column, $this->allowFilter)) {
                // Manejo especial para fechas
                if ($column === 'date') {
                    $query->whereDate($column, $value);
                } 
                // Manejo especial para rangos numéricos
                elseif ($column === 'amount' && is_array($value)) {
                    if (isset($value['min'])) {
                        $query->where($column, '>=', $value['min']);
                    }
                    if (isset($value['max'])) {
                        $query->where($column, '<=', $value['max']);
                    }
                } else {
                    $query->where($column, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));

        foreach ($sortFields as $field) {
            $direction = 'asc';
            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = substr($field, 1);
            }

            if (in_array($field, $this->allowSort)) {
                $query->orderBy($field, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            if ($perPage > 0) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }

    public function scopeRecent(Builder $query, $days = 30)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }
}