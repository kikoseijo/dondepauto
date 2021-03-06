<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 19/04/2016
 * Time: 9:21 AM
 */

namespace App\Repositories\Platform;

use App\Repositories\BaseRepository;
use Carbon\Carbon;

class ContactRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Entities\Platform\Contact';
    }

    /**
     * @param string $role
     * @param bool $show_notification
     * @param null $init
     * @param null $finish
     * @return mixed
     */
    protected function getDefaultActions($role = 'publisher', $show_notification = true, $init = null, $finish = null)
    {
        if(is_null($init) && is_null($finish)) {
            $init = Carbon::today()->toDateString();
            $finish = Carbon::tomorrow()->toDateString();
        }

        return $this->model
            ->with(['actions', 'user'])
            ->whereHas('actions', function($query) use ($init, $finish, $show_notification) {
                return $query->where('action_contact.action_at', '>=', $init)
                    ->where('action_contact.action_at', '<', $finish)
                    ->where('show_notifications', $show_notification);
            })
            ->whereHas('user', function($query) use ($role) {
                return $query->role($role);
            })
            ->orderBy('created_at', 'desc')
            ->groupBy('user_id');
    }


    /**
     * @param string $role
     * @param bool $show_notification
     * @param null $init
     * @param null $finish
     * @return mixed
     */
    public function getActions($role = 'publisher', $show_notification = true, $init = null, $finish = null)
    {
        return $this->getDefaultActions($role, $show_notification, $init, $finish)->get();
    }

    /**
     * @param string $role
     * @param bool $show_notification
     * @param null $init
     * @param null $finish
     * @return mixed
     */
    public function getCountActions($role = 'publisher', $show_notification = true, $init = null, $finish = null)
    {
        return $this->getActions($role, $show_notification, $init, $finish)->count();
    }




}