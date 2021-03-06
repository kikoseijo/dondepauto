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
use App\Services\ContactService;
use App\Services\DateService;
use App\Services\EmailService;
use App\Services\FilterCollectionService;
use App\Services\MailchimpService;
use App\Services\PasswordService;
use App\Services\RepresentativeService;
use App\Services\UserService;
use App\Services\PublisherService;
use App\Services\MixpanelService;
use App\Services\Space\SpaceService;
use App\Services\Platform\UserService as UserPlatformService;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class PublisherFacade extends UserFacade
{
    protected $service;
    protected $emailService;
    protected $spaceService;
    protected $confirmationService;
    protected $mixpanelService;
    protected $mailchimpService;
    protected $passwordService;
    protected $userService;
    protected $representativeService;
    protected $dateService;
    protected $filterCollectionService;

    public function __construct(PublisherService $service, EmailService $emailService, UserService $userService,
                                ConfirmationService $confirmationService, MixpanelService $mixpanelService,
                                MailchimpService $mailchimpService, SpaceService $spaceService, PasswordService $passwordService,
                                RepresentativeService $representativeService, DateService $dateService,
                                ContactService $contactService, UserPlatformService $userPlatformService, FilterCollectionService $filterCollectionService)
    {
        $this->service = $service;
        $this->emailService = $emailService;
        $this->confirmationService = $confirmationService;
        $this->mixpanelService = $mixpanelService;
        $this->mailchimpService = $mailchimpService;
        $this->spaceService = $spaceService;
        $this->passwordService = $passwordService;
        $this->userService = $userService;
        $this->representativeService = $representativeService;
        $this->dateService = $dateService;
        $this->filterCollectionService = $filterCollectionService;

        parent::__construct($userPlatformService, $contactService);
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
     * @param array $columns
     * @param $search
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(array $columns, $search)
    {
        return $this->service->search($columns, $search);
    }

    /**
     * @param array $columns
     * @param $search
     * @return Collection
     */
    public function searchAndFilter(array $columns, $search)
    {
        return $this->filterCollectionService->filterPublisherCollection($this->search($columns, $search), $columns);
    }

    public function getPublisher(User $publisher)
    {
        return $this->service->getPublisherView($publisher);
    }

    public function getPublisherId($id)
    {
        return $this->service->getPublisherViewId($id);
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
        $this->mixpanelService->registerUser($publisher);
        $this->mailchimpService->syncPublisher($publisher);

        return $publisher;
    }

    /**
     * @param array $data
     */
    public function createPublisher(array $data)
    {
        $publisher = $this->createModel($data);
        $this->createContact($publisher, $data);
    }

    /**
     * @param array $data
     * @param Model $publisher
     * @param bool $syncBool
     * @return mixed
     */
    public function updateModel(array $data, Model $publisher, $syncBool = true)
    {
        if(! array_key_exists('signed_agreement', $data)) {
            $data['signed_agreement'] = $publisher->has_signed_agreement;
        }

        \Log::info($data);

        $publisher = $this->service->updateModel($data, $publisher, $syncBool);
        $this->mixpanelService->updatePublisher($publisher);
        $this->mailchimpService->syncPublisher($publisher);

        return $publisher;
    }

    /**
     * @param array $data
     * @param Model $publisher
     * @return mixed
     */
    public function updateModelView(array $data, Model $publisher)
    {
        if(array_key_exists('repre', $data)) {
            $this->representativeService->createOrUpdate($data['repre'], $publisher, $publisher->representative);
        }

        return $this->service->getPublisherView($this->updateModel($data, $publisher, false));
    }

    /**
     * @param array $data
     * @param Model $publisher
     * @return Model|mixed
     */
    public function completeData(array $data, Model $publisher)
    {
        $publisher = $this->service->completeData($data, $publisher);
        $this->mixpanelService->updatePublisher($publisher);
        $this->mixpanelService->track("REGISTRO_COMPLEMENTARIO_DE_USUARIO", $publisher);
        $this->mailchimpService->syncPublisher($publisher);

        $this->mailchimpService->removeUserAutomation('complete-data', $publisher);
        $this->mailchimpService->addUserAutomation('create-offers', $publisher);

        return $publisher;
    }

    public function getSpaces(Model $publisher)
    {
        return $this->service->getSpaces($publisher);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function registerAutoPassword(array $data)
    {
        $password   = $this->passwordService->generate();
        $data       = $this->userService->divideName($data);
        $publisher  = $this->service->register($data, $password);
        $user       = $this->userService->createPublisher($data, $password, $publisher);
        
        $this->mixpanelService->registerUser($publisher);
        $confirmation = $this->confirmationService->generateConfirmation($publisher);
        $this->emailService->sendPublisherInvitation($publisher, $confirmation->code);
        
        return $user;
    }

    /**
     * @param User $publisher
     * @param $user
     * @param bool $remember
     */
    protected function login(User $publisher, $user, $remember = false)
    {
        Auth::loginUsingId($user->id, $remember);
        $this->trackLogin($publisher);
    }

    /**
     * @param User $publisher
     * @param bool $remember
     * @return User
     */
    public function loginPublisher(User $publisher, $remember = false)
    {
        $user = $publisher->user;

        if(! $user) {
            $user = $this->userService->createUserOfPublisher($publisher);
        }

        $this->login($publisher, $user, $remember);

        return $publisher;
    }


    /**
     * @param $code
     * @return User|bool
     */
    public function confirm($code)
    {
        $publisher = $this->confirmationService->verifyAndConfirm($code);
        $this->service->confirm($publisher);
        $this->mixpanelService->confirm($publisher);
        $this->mailchimpService->addUserAutomation('complete-data', $publisher);
        return $this->loginPublisher($publisher, true);
    }

    /**
     * @param User $user
     * @param array $dataPublisher
     * @param array $dataRepresentative
     */
    public function completeAgreement(User $user, array $dataPublisher, array $dataRepresentative)
    {
        $this->service->updateModel($dataPublisher + ['signed_at' => Carbon::now()->toDateString()], $user);
        $this->representativeService->createOrUpdate($dataRepresentative, $user, $user->representative);
    }

    /**
     * @param User $publisher
     * @param UploadedFile $commerceDocument
     * @param UploadedFile $rutDocument
     * @param UploadedFile $bankDocument
     * @param UploadedFile $letterDocument
     */
    public function saveDocuments(User $publisher, UploadedFile $commerceDocument = null, UploadedFile $rutDocument = null, UploadedFile $bankDocument = null, UploadedFile $letterDocument = null)
    {
        if(auth()->user()->isPublisher()) {
            $this->service->setChangeDocuments($publisher, false);
        }

        $this->service->saveDocuments($publisher, $commerceDocument, $rutDocument, $bankDocument, $letterDocument);
        $this->mailchimpService->removeUserAutomation('agreement', $publisher);
        $this->mailchimpService->addUserAutomation('documents', $publisher);
        $this->emailService->notifyDocuments($publisher);
    }

    /**
     * @param User $publisher
     * @return mixed
     */
    public function generateLetter(User $publisher)
    {
        $letter = $this->service->generateLetter($publisher, $this->dateService->getLangDateToday());
        $this->emailService->sendLetter($publisher, $letter['path'], $this->service->getTerms());
        return $letter['stream'];
    }

    /**
     * @param User $publisher
     * @param $comments
     */
    public function changeAgreement(User $publisher, $comments)
    {
        $this->emailService->changeAgreement($publisher, $comments);
    }


    /**
     * @param User $publisher
     */
    public function changeRole(User $publisher)
    {
        $this->service->changeRole($publisher);
        $user = $publisher->user;
        if(is_null($publisher->user)) {
            $user = $this->userService->createUserOfAdvertiser($publisher);
        }
        $this->userService->changeRole($user, 'advertiser');
        $this->mixpanelService->updateRoleUser($publisher);
        $this->mailchimpService->updateRoleUser($publisher);
        $this->emailService->notifyChangeAdvertiserRole($publisher);
    }

    /**
     * @param User $user
     * @param $agreement
     */
    public function setAgreement(User $user, $agreement)
    {
        $this->service->setAgreement($user, $agreement);

        $this->mailchimpService->addUserAutomation('verify_documents', $user);
    }

    /**
     * @param User $publisher
     * @param $changeDocuments
     */
    public function setChangeDocuments(User $publisher, $changeDocuments)
    {
        $this->service->setChangeDocuments($publisher, $changeDocuments);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deletePublisher(User $user)
    {
        $this->emailService->notifyUserDelete($user);
        $this->mailchimpService->removeUser($user);
        return $this->service->deleteModel($user);
    }

    /**
     * @param User $user
     * @param UploadedFile $logo
     */
    public function saveLogo(User $user, UploadedFile $logo)
    {
        $this->service->saveLogo($user, $logo);
    }

    /**
     * @param User $user
     */
    public function trackLogin(User $user)
    {
        $code = $this->userPlatformService->trackLogin($user);
        $this->mixpanelService->trackLogin($user);
        session(['login_code' => $code]);
    }

    /**
     * @param User $user
     */
    public function trackLogout(User $user)
    {
        $this->userPlatformService->trackLogout($user, session('login_code'));
    }

    /**
     * @param $id
     * @return array
     */
    public function getStates($id)
    {
        return $this->service->getStates($id);
    }

}