<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\User;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UsersRepositories.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    public function getPaginateStudents(): LengthAwarePaginator
    {
        return $this->model->with('createdBy')
            ->paginate(10);
    }

    /**
     * Create user
     *
     * @param array $params
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function createUser(array $params): mixed
    {
        $create = $this->create($params);
        $create->roles()->sync($params['roles']);

        return $create;
    }

    public function countActiveUserByIds(array $userIds)
    {
        return $this->model->whereIn('id', $userIds)
            ->isActive()->count();
    }

    public function getHistoryStudentsOfTest($testId)
    {
        return $this->model
            ->select('users.*')
            ->join('test_user', 'test_user.user_id', '=', 'users.id')
            ->join('tests', 'tests.id', '=', 'test_user.test_id')
            ->where('tests.id', $testId)
            ->with(['examSessions' => function ($query) use ($testId) {
                $query->where('test_id', $testId);
            }])->get();
    }

    public function getExamSessionsOfStudentWithTestId($testId, $studentId)
    {
        return $this->model->select('users.*',
            'tests.desc as test_desc', 'tests.start_time as test_start_time', 'tests.end_time as test_end_time')
            ->join('test_user', 'test_user.user_id', '=', 'users.id')
            ->join('tests', 'tests.id', '=', 'test_user.test_id')
            ->where('tests.id', $testId)
            ->with(['examSessions' => function ($query) use ($testId) {
                $query->where('test_id', $testId);
                $query->with('exam.skills');
                $query->with(['skillSessions' => function ($query) {
                    $query->orderByDesc('id');
                    $query->with('skill');
                }]);
            }])->findOrFail($studentId);
    }
}
