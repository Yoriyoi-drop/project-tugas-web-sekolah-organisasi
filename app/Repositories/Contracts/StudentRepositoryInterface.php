<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{
    public function findByNis($nis);
    public function findByEmail($email);
    public function findByClass($class);
    public function search($query);
    public function getActiveStudents();
    public function getStudentCount();
}
