<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 26/04/2016
 * Time: 10:12 AM
 */

namespace App\Services;

use App\Entities\Platform\User;
use Carbon\Carbon;
use GeneaLabs\LaravelMixpanel\LaravelMixpanel;

class MixpanelService
{
    protected $mixPanel;

    public function __construct(LaravelMixpanel $mixPanel)
    {
        $this->mixPanel = $mixPanel;
    }

    /**
     * @param User $user
     */
    public function trackLogin(User $user)
    {
        $this->track("LOGIN_DE_USUARIO", $user);

        $this->updateUser($user, [
            '$last_login' => Carbon::now()->toDateTimeString()
        ]);
    }

    /**
     * @param User $user
     */
    public function updateRoleUser(User $user)
    {
        $this->updateUser($user, [
            'DP - User type'  => $user->tipo_us_LI
        ]);
    }

    public function registerUser(User $user)
    {
        if(config('app.env') == 'production') {
            $this->mixPanel->people->set($user->id, [
                '$first_name'                        => $user->first_name,
                '$last_name'                         => $user->last_name,
                '$created'                           => $user->created_at,
                '$email'                             => $user->email,
                'DP - User type'                    => $user->tipo_us_LI,
                "DP - User id"                      => $user->id,
                "DP - Email verified"               => 'No',
                "DP - User status"                  => "pendAceptarInvitacion",
                "DP - User suscription plan"        => "1",
                "DP - User suscription plan deadline" => "0000-00-00"
            ]);
        }
    }

    /**
     * @param $even
     * @param User $user
     */
    public function track($even, User $user)
    {
        $this->mixPanel->identify($user->id);

        $this->mixPanel->track($even, [
            "DP - User id"  => $user->id,
            "DP - User email" => $user->email
        ]);
    }

    protected function updateUser(User $user, array $data)
    {
        if(config('app.env') == 'production') {
            $this->mixPanel->people->set($user->id, $data);
        }
    }

    public function confirm(User $user)
    {
        $this->updateUser($user, [
            "DP - Email verified"   => 'Si',
            '$last_login'           => Carbon::now()->toDateTimeString()
        ]);

        $this->track("ACTIVACION_DE_USUARIO", $user);
        $this->track("FORMULARIO_REGISTRO_COMPLEMENTARIO_DE_USUARIOS", $user);
    }

    public function updateAdvertiser(User $advertiser)
    {
        $this->updateUser($advertiser, [
            '$first_name'                        => $advertiser->first_name,
            '$last_name'                         => $advertiser->last_name,
            '$email'                             => $advertiser->email,
            '$created'                           => $advertiser->created_at_mixpanel,
            'DP - Company name'					=> $advertiser->company,
            'DP - Company city'					=> $advertiser->city_name,
            'DP - Company economic activity'	=> $advertiser->activity_name,
            'DP - Company NIT'					=> $advertiser->company_nit,
            'DP - Company address'				=> $advertiser->address,
            'DP - User position area'			=> $advertiser->company_area,
            'DP - User position'				=> $advertiser->company_role,
            'DP - User cellphone'				=> $advertiser->cel,
            'DP - User phone'					=> $advertiser->phone
        ]);
    }

    public function updatePublisher(User $publisher)
    {
        $this->updateUser($publisher, [
            '$first_name'                       => $publisher->first_name,
            '$last_name'                        => $publisher->last_name,
            '$email'                            => $publisher->email,
            '$created'                          => $publisher->created_at_mixpanel,
            'DP - Company name'					=> $publisher->company,
            'DP - Company city'					=> $publisher->city_name,
            'DP - Company NIT'					=> $publisher->company_nit,
            'DP - Company address'				=> $publisher->address,
            'DP - User position area'			=> $publisher->company_area,
            'DP - User position'				=> $publisher->company_role,
            'DP - User cellphone'				=> $publisher->cel,
            'DP - User phone'					=> $publisher->phone,
            'PD - User agreement'               => $publisher->signed_agreement_lang
        ]);
    }
}

