<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 21/04/2016
 * Time: 11:50 AM
 */

namespace App\Services;

use App\Entities\Platform\User;
use App\Repositories\File\PublisherDocumentsRepository;
use App\Repositories\Platform\UserRepository;
use App\Repositories\Views\PublisherRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class PublisherService extends ResourceService
{
    protected $viewRepository;
    protected $publisherDocumentsRepository;

    /**
     * UserService constructor.
     * @param PublisherRepository $viewRepository
     * @param UserRepository $repository
     * @param PublisherDocumentsRepository $publisherDocumentsRepository
     */
    function __construct(PublisherRepository $viewRepository, UserRepository $repository, PublisherDocumentsRepository $publisherDocumentsRepository)
    {
        $this->viewRepository = $viewRepository;
        $this->repository = $repository;
        $this->publisherDocumentsRepository = $publisherDocumentsRepository;
    }
    
    /**
     * @param array $columns
     * @param array $search
     * @return mixed
     */
    public function search(array $columns, array $search)
    {
        return $this->viewRepository->search($columns, $search);
    }

    /**
     * @param array $data
     * @param Model $publisher
     * @return mixed
     */
    public function completeData(array $data, Model $publisher)
    {
        $data['complete_data'] = true;
        return $this->updateModel($data, $publisher);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createModel(array $data)
    {
        $data['role'] = 'publisher';
        $data['complete_data'] = false;
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
        return $this->viewRepository->publishersWithSpaces($category_id, $subCategory_id, $format_id, $city_id);
    }

    /**
     * @param $publisher
     * @return mixed
     */
    public function getSpaces($publisher)
    {
        return $this->repository->getSpaces($publisher);
    }

    /**
     * @param array $data
     * @param null $password
     * @return mixed
     */
    public function register(array $data, $password = null)
    {
        if(! is_null($password)) {
            $data['password'] = $password;
        }

        return $this->createModel($data);
    }


    /**
     * @param User $publisher
     * @param UploadedFile $commerceDocument
     * @param UploadedFile $rutDocument
     * @param UploadedFile $bankDocument
     * @param UploadedFile $letterDocument
     */
    public function saveDocuments(User $publisher, UploadedFile $commerceDocument, UploadedFile $rutDocument, UploadedFile $bankDocument, UploadedFile $letterDocument)
    {
        $this->publisherDocumentsRepository->saveCommerceDocument($publisher, $commerceDocument);
        $this->publisherDocumentsRepository->saveRutDocument($publisher, $rutDocument);
        $this->publisherDocumentsRepository->saveBankDocument($publisher, $bankDocument);
        $this->publisherDocumentsRepository->saveLetterDocument($publisher, $letterDocument);
    }

    /**
     * @param User $publisher
     * @param $dateString
     * @return mixed
     */
    public function generateLetter(User $publisher, $dateString)
    {
        return $this->publisherDocumentsRepository->generateLetter($publisher, $dateString);
    }

    /**
     * @return string
     */
    public function getTerms()
    {
        return $this->publisherDocumentsRepository->getTerms();
    }

    public function findOrCreateUser(User $publisher)
    {
        if(! $publisher->user) {
            $this;
        }

        dd($publisher->user);
    }

}