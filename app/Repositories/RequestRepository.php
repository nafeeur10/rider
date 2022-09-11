<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class RequestRepository extends Repository
{
    public function create(array $data): Model {
        return $this->model->create($data);
    }

    public function update(array $data):void {
        $this->model->update($data);
    }
}
