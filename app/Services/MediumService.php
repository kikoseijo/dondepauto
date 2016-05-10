<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 21/04/2016
 * Time: 11:50 AM
 */

namespace App\Services;

use App\Repositories\Platform\UserRepository;
use App\Repositories\Views\MediumRepository;

class MediumService extends ResourceService
{
    protected $viewRepository;

    /**
     * UserService constructor.
     * @param MediumRepository $viewRepository
     * @param UserRepository $repository
     */
    function __construct(MediumRepository $viewRepository, UserRepository $repository)
    {
        $this->viewRepository = $viewRepository;
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search()
    {
        return ['data' => $this->viewRepository->search()];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createModel(array $data)
    {
        $data['role'] = 'medium';
        return $this->repository->create($data);
    }

    /**
     * @param null $category_id
     * @param null $subCategory_id
     * @param null $format_id
     * @param null $city_id
     * @return mixed
     */
    public function searchWithSpaces($category_id = null, $subCategory_id = null, $format_id = null, $city_id = null)
    {
        return $this->viewRepository->mediumsWithSpaces($category_id, $subCategory_id, $format_id, $city_id);
    }
}