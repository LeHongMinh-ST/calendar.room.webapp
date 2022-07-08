<?php

namespace App\Providers;

use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\Faculty\FacultyRepository;
use App\Repositories\Faculty\FacultyRepositoryInterface;
use App\Repositories\Feedback\FeedbackRepository;
use App\Repositories\Feedback\FeedbackRepositoryInterface;
use App\Repositories\Room\RoomRepository;
use App\Repositories\Room\RoomRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\Semester\SemesterRepository;
use App\Repositories\Semester\SemesterRepositoryInterface;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Week\WeekRepository;
use App\Repositories\Week\WeekRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(FacultyRepositoryInterface::class, FacultyRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(SemesterRepositoryInterface::class, SemesterRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
        $this->app->bind(WeekRepositoryInterface::class, WeekRepository::class);
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
