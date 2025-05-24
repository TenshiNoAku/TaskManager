<?php

namespace App\DTO;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorMetaDTO
{
    public readonly int $cur_page;
    public readonly int $per_page;
    public readonly int $total_count;
    public readonly int $total_pages;

    public readonly int $count;
    public function __construct(Paginator $paginator) {
        $query = $paginator->getQuery();
        $this->per_page = $query->getMaxResults();
        $this->total_count = $paginator->count();
        $this->total_pages = ceil($this->total_count / $this->per_page);
        $this->cur_page =  $query->getFirstResult() / $this->per_page +1 ; ;

        $this->count = count(iterator_to_array($paginator->getIterator()));
    }

    public function toArray(): array {
        return [
            "count" => $this->count,
            "cur_page" => $this->cur_page,
            "per_page" => $this->per_page,
            "total_count" => $this->total_count,
            "total_pages" => $this->total_pages
        ];
    }
}