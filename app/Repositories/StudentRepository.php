<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function findByNis($nis)
    {
        return $this->model->where('nis', $nis)->first();
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByClass($class)
    {
        return $this->model->where('class', $class)->get();
    }

    public function search($query)
    {
        return $this->model->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('nis', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('class', 'like', "%{$query}%");
        })->get();
    }

    public function getActiveStudents()
    {
        return $this->model->orderBy('name')->get();
    }

    public function getStudentCount()
    {
        return $this->model->count();
    }

    public function getStudentsWithFilters(array $filters = [], $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (isset($filters['name']) && $filters['name']) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }

        if (isset($filters['nis']) && $filters['nis']) {
            $query->where('nis', 'like', "%{$filters['nis']}%");
        }

        if (isset($filters['email']) && $filters['email']) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }

        if (isset($filters['class']) && $filters['class']) {
            $query->where('class', $filters['class']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function createStudent(array $data)
    {
        // Validate unique fields before creating
        if ($this->findByNis($data['nis'])) {
            throw new \Exception('NIS already exists');
        }

        if ($this->findByEmail($data['email'])) {
            throw new \Exception('Email already exists');
        }

        return $this->create($data);
    }

    public function updateStudent($id, array $data)
    {
        $student = $this->findOrFail($id);

        // Check if NIS is being changed and if it's unique
        if (isset($data['nis']) && $data['nis'] !== $student->nis) {
            if ($this->findByNis($data['nis'])) {
                throw new \Exception('NIS already exists');
            }
        }

        // Check if email is being changed and if it's unique
        if (isset($data['email']) && $data['email'] !== $student->email) {
            if ($this->findByEmail($data['email'])) {
                throw new \Exception('Email already exists');
            }
        }

        return $this->update($id, $data);
    }
}
