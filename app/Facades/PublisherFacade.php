<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 21/04/2016
 * Time: 11:48 AM
 */

namespace App\Facades;

use App\Entities\Platform\User;
use App\Services\ConfirmationService;
use App\Services\EmailService;
use App\Services\MailchimpService;
use App\Services\PublisherService;
use App\Services\MixpanelService;
use App\Services\Space\SpaceService;
use Illuminate\Database\Eloquent\Model;

class PublisherFacade
{
    protected $service;
    protected $emailService;
    protected $spaceService;
    protected $confirmationService;
    protected $mixpanelService;
    protected $mailchimpService;

    public function __construct(PublisherService $service, EmailService $emailService, 
                                ConfirmationService $confirmationService, MixpanelService $mixpanelService,
                                MailchimpService $mailchimpService, SpaceService $spaceService)
    {
        $this->service = $service;
        $this->emailService = $emailService;
        $this->confirmationService = $confirmationService;
        $this->mixpanelService = $mixpanelService;
        $this->mailchimpService = $mailchimpService;
        $this->spaceService = $spaceService;
    }

    /**
     * @param User $publisher
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchSpaces(User $publisher)
    {
        return $this->spaceService->search($publisher);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search()
    {
        return $this->service->search();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createModel(array $data)
    {
        $publisher = $this->service->createModel($data);
        $confirmation = $this->confirmationService->generateConfirmation($publisher);
        $this->emailService->sendPublisherInvitation($publisher, $confirmation->code);
        //$this->mixpanelService->registerUser($publisher);
        $this->mailchimpService->syncPublisher($publisher);
        
        return $publisher;
    }

    /**
     * @param array $data
     * @param Model $publisher
     * @return mixed
     */
    public function updateModel(array $data, Model $publisher)
    {
        $publisher = $this->service->updateModel($data, $publisher);
        //$this->mixpanelService->updatePublisher($publisher);
        $this->mailchimpService->syncPublisher($publisher);

        return $publisher;
    }

    public function getSpaces(Model $publisher)
    {
        return $this->service->getSpaces($publisher);
    }
    
}