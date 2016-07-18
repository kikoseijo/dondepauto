<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 12/07/2016
 * Time: 6:22 PM
 */

namespace App\Repositories\File;


use App\Entities\Platform\User;
use Illuminate\Http\UploadedFile;

class PublisherDocumentsRepository extends BaseRepository
{
    protected $path = "documents/publishers";


    /**
     * @param User $publisher
     * @return string
     */
    public function getPathPublisher(User $publisher)
    {
        return $this->getPath() . '/' . $publisher->id;
    }

    /**
     * @param User $publisher
     * @param UploadedFile $document
     * @param $name
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    protected function saveDocument(User $publisher, UploadedFile $document, $name)
    {
        return $this->isValidMove($document, $this->getPathPublisher($publisher), $name . '.pdf');
    }


    /**
     * @param User $publisher
     * @param UploadedFile $document
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    public function saveCommerceDocument(User $publisher, UploadedFile $document)
    {
        return $this->saveDocument($publisher, $document, 'commerce');
    }

    /**
     * @param User $publisher
     * @param UploadedFile $document
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    public function saveBankDocument(User $publisher, UploadedFile $document)
    {
        return $this->saveDocument($publisher, $document, 'bank');
    }

    /**
     * @param User $publisher
     * @param UploadedFile $document
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    public function saveRutDocument(User $publisher, UploadedFile $document)
    {
        return $this->saveDocument($publisher, $document, 'rut');
    }

    /**
     * @param User $publisher
     * @param UploadedFile $document
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    public function saveLetterDocument(User $publisher, UploadedFile $document)
    {
        return $this->saveDocument($publisher, $document, 'letter');
    }
}