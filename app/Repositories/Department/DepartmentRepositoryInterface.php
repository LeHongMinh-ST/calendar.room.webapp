<?php

namespace App\Repositories\Department;

use App\Repositories\Base\BaseRepositoryInterface;

interface DepartmentRepositoryInterface extends BaseRepositoryInterface
{
    public function getDepartmentFilters(array $filters, array $relations = []);
}