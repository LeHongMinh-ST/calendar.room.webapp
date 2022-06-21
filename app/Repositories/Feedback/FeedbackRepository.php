<?php

namespace App\Repositories\Feedback;

use App\Models\Feedback;
use App\Repositories\Base\BaseRepository;

class FeedbackRepository extends BaseRepository implements FeedbackRepositoryInterface
{
    public function __construct(Feedback $model)
    {
        parent::__construct($model);
    }
}
