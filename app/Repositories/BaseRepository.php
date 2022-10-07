<?php

namespace App\Repositories;

class BaseRepository
{
    public function create($model,array $inputs)
    {
        $data = $model->create($inputs);
        return $data;
    }

    public function update($model, $id,array $inputs)
    {
        $data = $model->findOrFail($id);
        $data->update($inputs);
        return $data;
    }

    public function delete($model, $id)
    {
        $data = $model->findOrFail($id);
        return $data->delete();
    }

    public function search($model, array $inputs)
    {
        $query = $model->offset(($inputs['page'] - 1) * $inputs['limit'])->limit($inputs['limit']);
        if (!empty($inputs['kw'])) {
            $query->whereRaw('reference like \'%' . $inputs['kw'] . '%\'');
            $query->whereRaw('name like \'%' . $inputs['kw'] . '%\'');
        }
        $data = $query->orderBy($inputs['order_by'], $inputs['sort'])->get();
        return $data;
    }

    public function check($model, $field, array $input){
        $query = $model->where($field, $input[$field]);
        if(isset($inputs['id']) &&empty($inputs['id'])){
            $query->where('id', '<>', $input['id']);
        }
        $check = $query->first();
        return $check;
    }

    public function findId($model, $input){
        $data = $model->where('id', '=', $input);
        return $data;
    }
}
