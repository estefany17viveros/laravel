<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
class orderitem extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity', 
        'price', 
        'order_id', 
        'product_id', 
    ];
    public function order()
    {
        return $this->belongsTo(order::class);
}
    public function product()
    {
        return $this->belongsTo(product::class);
    }
        protected $allowFilter = ['price', 'quantity'];

      protected function getAllowIncluded()
{
    // Definir explícitamente las relaciones que se pueden incluir
    return ['order', 'product'];
}
    // 🔍 Scope para permitir ?included=relacion1,relacion2
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
                $query->where($column, 'LIKE', '%' . $value . '%');
            }
        }
        
    }
      public function scopeGetOrPaginate(Builder $query)
    {
      if (request('perPage')) {
            $perPage = intval(request('perPage'));//transformamos la cadena que llega en un numero.

            if($perPage){//como la funcion intval retorna 0 si no puede hacer la conversion 0  es = false
                return $query->paginate($perPage);//retornamos la cuonsulta de acuerdo a la ingresado en la vaiable $perPage
            }


         }
           return $query->get();//sino se pasa el valor de $perPage en la URL se pasan todos los registros.
        //http://api.codersfree1.test/v1/categories?perPage=2
    }

      
// App\Models\Forum.php

public function scopeSort($query)
{
    if (request()->has('sort_by') && request()->has('sort_direction')) {
        $column = request('sort_by');
        $direction = request('sort_direction');

        // Validar columnas permitidas
        $allowed = ['title', 'creation_date'];
        if (in_array($column, $allowed)) {
            return $query->orderBy($column, $direction);
        }
    }}

}
