<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'is_read', // Campo añadido para estado de lectura
        'type' // Campo añadido para tipo de notificación
    ];

    // Configuración para consultas
    protected $allowFilter = [
        'id',
        'title',
        'user_id',
        'is_read',
        'type',
        'created_at'
    ];
    
    protected $allowSort = [
        'id',
        'title',
        'created_at'
    ];
    
    protected $allowIncluded = [
        'user',
        'user.profile' // Relación anidada
    ];

    // Casts
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
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
                // Manejo especial para booleanos
                if ($column === 'is_read') {
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

    // Scope adicional para notificaciones no leídas
    public function scopeUnread(Builder $query)
    {
        return $query->where('is_read', false);
    }
}