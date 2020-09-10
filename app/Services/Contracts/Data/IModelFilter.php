<?php

namespace App\Services\Contracts\Data;


interface IModelFilter
{

    /**
     * @param $model
     * @param IModelRepository $repository
     * @return mixed
     */
    public function apply($model);

    /**
     * Set the classs $query_criteria property to the value of the parameter
     *
     * @param array $list
     * @return static
     */
    public function setQueryFilters(array $list);
}
