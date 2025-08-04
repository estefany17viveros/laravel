<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

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
    ];

    protected $allowIncluded = ['payable', 'user', 'paymentMethod'];
    protected $allowFilter = ['id', 'amount', 'date', 'status'];
    protected $allowSort = ['id', 'amount', 'date'];

    // ✅ Relaciones
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

    // ✅ Scope para incluir relaciones (soporta nested relations tipo payable.payments)
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return $query;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        $relations = array_filter($relations, fn($rel) => $allowIncluded->contains($rel));

        return $query->with($relations);
    }

    // ✅ Scope para filtros dinámicos
    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return $query;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $column => $value) {
            if ($allowFilter->contains($column)) {
                $query->where($column, 'LIKE', "%$value%");
            }
        }

        return $query;
    }

    // ✅ Scope para orden dinámico
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

        return $query;
    }

    // ✅ Scope para obtener todo o paginar
    public function scopeGetOrPaginate(Builder $query)
    {
        return request('perPage')
            ? $query->paginate((int) request('perPage'))
            : $query->get();
    }
}
