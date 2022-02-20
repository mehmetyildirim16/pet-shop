<?php

namespace App\Utils;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PaginatorHelper
{


    /**
     *
     * @param Collection $items
     * @param int              $perPage
     * @param ?int              $page
     * @param array            $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate(Collection $items, int $perPage = 15, int $page = null, array $options = [])
    {
        $page = $page ? : (Paginator::resolveCurrentPage() ? : 1);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
