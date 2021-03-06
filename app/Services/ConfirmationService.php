<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 26/04/2016
 * Time: 9:14 AM
 */

namespace App\Services;


use App\Entities\Platform\User;
use App\Repositories\Platform\ConfirmationRepository;

class ConfirmationService extends ResourceService
{

    /**
     * ConfirmationService constructor.
     * @param ConfirmationRepository $repository
     */
    public function __construct(ConfirmationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function generateConfirmation(User $user)
    {
        $code = str_random(30);
        return $this->repository->generateConfirmation($user, $code);
    }

    /**
     * @param $code
     * @return bool
     */
    public function verify($code)
    {
        return $this->repository->isActive($code);    
    }

    /**
     * @param $code
     * @return User
     */
    public function getUser($code)
    {
        return $this->repository->getUser($code);
    } 

    /**
     * @param $code
     * @return User
     */
    public function confirm($code)
    {
        return $this->repository->confirm($code);
    }

    /**
     * @param $code
     * @return User
     */
    function verifyAndConfirm($code)
    {
        $confirmation = $this->repository->findOrFailByCode($code);

        if(! $confirmation->active){
            return $this->repository->confirmModel($confirmation);
        }

        return $confirmation->user;
    }
}