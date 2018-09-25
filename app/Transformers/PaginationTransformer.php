<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Builder;

class PaginationTransformer {

    /**
     * Method to paginate Eloquent query records
     * 
     * @param Builder builder
     * @param int paginate
     * 
     * @return Builder paginate
     */
    public static function paginate($builder, int $paginate = null)
    {
        return $builder->paginate ((int) $paginate ?? env ('DEFAULT_PAGINATION'));
    }
}