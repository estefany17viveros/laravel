<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'types',
        'details',
        'expiration_date',
        'CCV',
        'is_default', // Campo añadido
        'user_id' // Campo añadido para relación con usuario
    ];

    // Configuración para consultas
    protected $allowFilter = [
        'id',
        'types',
        'expiration_date',
        'is_default',
        'user_id'
    ];
    
    protected $allowSort = [
        'id',
        'types',
        'expiration_date',
        'created_at'
    ];
    
    protected $allowIncluded = [
        'user', // Relación con usuario
        'payments' // Relación con pagos
    ];

    // Casts
    protected $casts = [
        'expiration_date' => 'date',
        'is_default' => 'boolean'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes optimizados
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
                if ($column === 'expiration_date') {
                    $query->whereDate($column, $value);
                } 
                // Manejo especial para booleanos
                elseif ($column === 'is_default') {
                    $query->where($column, filter_var($value, FILTER_VALIDATE_BOOLEAN));
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

    // Scope para métodos de pago activos (no expirados)
    public function scopeActive(Builder $query)
    {
        return $query->where('expiration_date', '>', now());
    }

    // Scope para métodos por defecto
    public function scopeDefault(Builder $query)
    {
        return $query->where('is_default', true);
    }
}