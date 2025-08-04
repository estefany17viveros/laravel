<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'date',
        'description',
        'user_id',
        'veterinarian_id',
        'service_id',
        'schedule_id',
        'trainer_id',
        'pet_id',
    ];

    // Configuración para consultas
    protected $allowFilter = ['id', 'status', 'date', 'user_id', 'veterinarian_id', 'service_id', 'pet_id'];
    protected $allowSort = ['id', 'date', 'status', 'created_at'];
    protected $dates = ['date']; // Para manejar automáticamente como Carbon

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    protected function getAllowIncluded()
    {
        return ['user', 'veterinarian', 'service', 'schedule', 'trainer', 'pet'];
    }

    // Scopes para consultas anidadas
    public function scopeIncluded(Builder $query)
    {
        $allowIncluded = $this->getAllowIncluded();

        if (!request()->filled('included')) {
            return $query;
        }

        $relations = explode(',', request('included'));

        $filtered = array_filter($relations, function ($relation) use ($allowIncluded) {
            $root = explode('.', $relation)[0];
            return in_array($root, $allowIncluded);
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
            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}