<?php

namespace App\Repositories\Week;

use App\Repositories\Base\BaseRepositoryInterface;

interface WeekRepositoryInterface extends BaseRepositoryInterface
{
    public function createBySemester($data, $semester);
}
