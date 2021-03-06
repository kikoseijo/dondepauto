<?php
/**
 * Created by PhpStorm.
 * Users: Desarrollador 1
 * Date: 13/04/2016
 * Time: 10:26 AM
 */

namespace App\Repositories\Platform;


use App\Entities\User;
use App\Entities\Platform\User as UserPlatform;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Entities\Platform\User';
    }

    /**
     * @param array $advertiserIds
     * @param User $user
     * @return mixed
     */
    public function unlinkAdvertisers(array $advertiserIds, User $user)
    {
        return $this->model
                ->role('advertiser')
                ->ofUser($user->id)
                ->inIds($advertiserIds)
                ->update(['user_id' => null]);
    }

    /**
     * @param array $advertiserIds
     * @param User $user
     * @return mixed
     */
    public function linkAdvertisers(array $advertiserIds, User $user)
    {
        return $this->model
            ->role('advertiser')
            ->inIds($advertiserIds)
            ->update(['user_id' => $user->id]);
    }
    
    /**
     * @return Collection
     */
    public function advertisersWithOutAdviser()
    {
        return $this->model
            ->role('advertiser')
            ->whereNull('user_id')
            ->select(['id_us_LI as id', 'nombre_us_LI as first_name', 'apellido_us_LI as last_name', 
                'email_us_LI as email', 'empresa_us_LI as company', 'id_us_LI as DT_RowId'])
            ->get();
    }

    /**
     * @param array $data
     * @param Model $entity
     * @param bool $syncBool
     * @return mixed
     */
    public function update(array $data, $entity, $syncBool = true)
    {
        if(! array_key_exists('signed_agreement', $data))
        {
            $data['signed_agreement'] = 0;
        }
        
        return parent::update($data, $entity, $syncBool);
    }

    /**
     * @param \App\Entities\Platform\User $advertiser
     * @param $mailchimpId
     * @return bool
     */
    public function setMailchimpId(\App\Entities\Platform\User &$advertiser, $mailchimpId)
    {
        $advertiser->mailchimp_id = $mailchimpId;
        return $advertiser->save();
    }

    public function getSpaces(\App\Entities\Platform\User $publisher)
    {
        return $publisher->spaces()
            ->with(['images', 'format.subCategory.category'])
            ->orderBy('nombre_espacio_LI')
            ->paginate(12);
    }

    public function register(array $data)
    {
        return $this->create($data);
    }

    /**
     * @param int $differenceDays
     * @return mixed
     */
    public function getPublisherInComplete($differenceDays = 3)
    {
        return $this->model->with('confirmation')
            ->complete(false)
            ->role('publisher')
            ->get()
            ->filter(function ($publisher, $key) use($differenceDays) {
                return $publisher->email_validated && Carbon::now()->diffInDays($publisher->activated_at) == $differenceDays;
            })->count();
    }

    /**
     * @param int $differenceDays
     * @return mixed
     */
    public function getPublisherNotOffers($differenceDays = 3)
    {
        return $this->model->with('spaces')
            ->complete(true)
            ->role('publisher')
            ->get()
            ->filter(function ($publisher, $key) use($differenceDays) {
                return ! $publisher->has_offers && Carbon::now()->diffInDays($publisher->completed_at) == $differenceDays;
            })->count();
    }

    /**
     * @param int $differenceDays
     * @return mixed
     */
    public function getPublisherHasOffers($differenceDays = 3)
    {
        return $this->model->with('spaces')
            ->complete(true)
            ->role('publisher')
            ->get()
            ->filter(function ($publisher, $key) use($differenceDays) {
                return $publisher->has_offers && Carbon::now()->diffInDays($publisher->last_offert_created_at) == $differenceDays;
            })->count();
    }


    /**
     * @param int $differenceDays
     * @return mixed
     */
    public function getPublisherNotSigned($differenceDays = 3)
    {
        return $this->model->with('spaces')
            ->complete(true)
            ->hasSigned(false)
            ->role('publisher')
            ->get()
            ->filter(function ($publisher, $key) use($differenceDays) {
                return Carbon::now()->diffInDays($publisher->completed_at) == $differenceDays;
            })->count();
    }

    /**
     * @param UserPlatform $user
     * @return bool
     */
    public function changeRole(UserPlatform $user, $role)
    {
        $user->role = $role;
        return $user->save();
    }


    /**
     * @param UserPlatform $user
     * @param null $comments
     * @param int $type
     * @param array|null $data
     * @return Model
     */
    public function createContact(UserPlatform $user, $comments = null, $type = 1, array $data = null)
    {
        return $user->contacts()->create(array_merge(['comments' => $comments, 'type' => $type], $data));
    }

    /**
     * @param UserPlatform $publisher
     * @param $agreement
     * @return bool
     */
    public function setAgreement(UserPlatform $publisher, $agreement)
    {
        $publisher->signed_agreement = $agreement;

        if($agreement) {
            $publisher->signed_at = Carbon::now()->toDateString();
        }

        return $publisher->save();
    }

    /**
     * @param UserPlatform $publisher
     * @param $changeDocuments
     * @return bool
     */
    public function setChangeDocuments(UserPlatform $publisher, $changeDocuments)
    {
        $publisher->change_documents = $changeDocuments;
        return $publisher->save();
    }


    /**
     * @param UserPlatform $user
     * @return int
     */
    public function trackLogin(UserPlatform $user)
    {
        $code = rand(1,9000000000000);

        $user->logs()->create([
            'code_log_LI' => $code,
            'fecha_login_LI' => Carbon::now()->toDateTimeString(),
            'sesion_abandonada_LI' => true
        ]);

        return $code;
    }

    /**
     * @param UserPlatform $user
     * @param $code
     */
    public function trackLogout(UserPlatform $user, $code)
    {
        \Log::info('logout');
        $log = $user->logs()->where('code_log_LI', $code)->first();

        if($log) {
            $log->logout_at = Carbon::now()->toDateTimeString();
            $log->abandoned = false;
            $log->save();
        }
    }


    /**
     * @param UserPlatform $user
     * @return UserPlatform
     */
    public function confirm(UserPlatform $user)
    {
        $user->activated_at = Carbon::now()->toDateTimeString();
        $user->save();

        return $user;
    }

    /**
     * @param $userIds
     * @return mixed
     */
    public function getUsers(array $userIds)
    {
        return $this->model->whereIn('id_us_LI', $userIds)->get();
    }
}