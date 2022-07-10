<?php

namespace App\Repositories\Semester;

use App\Repositories\Base\BaseRepositoryInterface;

interface SemesterRepositoryInterface extends BaseRepositoryInterface
{
    public function checkTime($id, $data);
}
