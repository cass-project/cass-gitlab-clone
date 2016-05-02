<?php
namespace PostAttachment\Service;

use Common\Util\FileNameFilter;
use PostAttachment\Entity\PostAttachment;
use PostAttachment\Entity\PostAttachment\AttachmentType;
use PostAttachment\Entity\PostAttachment\File\GenericFileAttachmentType;
use PostAttachment\Entity\PostAttachment\File\ImageAttachmentType;
use PostAttachment\Entity\PostAttachment\File\WebmAttachmentType;
use PostAttachment\Entity\PostAttachment\FileAttachmentType;
use PostAttachment\Exception\FileTooBigException;
use PostAttachment\Exception\FileTooSmallException;
use PostAttachment\Repository\PostAttachmentRepository;

class PostAttachmentService
{
    /** @var string */
    private $uploadDir;

    /** @var PostAttachmentRepository */
    private $postAttachmentRepository;


    public function __construct(
        string $storageDir,
        string $uploadDir,
        PostAttachmentRepository $postAttachmentRepository
    ) {
        $this->uploadDir = $storageDir.'/'.$uploadDir;
        $this->postAttachmentRepository = $postAttachmentRepository;
    }

    public function uploadAttachment(string $tmpFile, string $desiredFileName): PostAttachment
    {
        $desiredFileName = FileNameFilter::filter($desiredFileName);

        $attachmentType = $this->factoryAttachmentType($tmpFile);

        if($attachmentType instanceof FileAttachmentType) {
            $this->validateFileSize($tmpFile, $attachmentType);
        }

        $postAttachmentEntity = $this->postAttachmentRepository->makePostAttachmentEntity($attachmentType->getCode());

        $directory = $this->uploadDir;
        $subDirectory = $postAttachmentEntity->getId();
        $resultDir = $directory.'/'.$subDirectory;

        if(mkdir($resultDir) === false) {
            throw new \Exception('Failed to create subdirectory');
        }

        if(move_uploaded_file($tmpFile, $resultDir.'/'.$desiredFileName) === false) {
            throw new \Exception('Failed to move uploaded file');
        }

        $postAttachmentEntity->setAttachment([
            'file' => [
                'public' => '/public/storage/post/attachment/'.$subDirectory.'/'.$desiredFileName,
                'storage' => $resultDir.'/'.$desiredFileName
            ]
        ]);

        if($attachmentType instanceof AttachmentTypeExtension) {
            $postAttachmentEntity->mergeAttachment($attachmentType->extend($postAttachmentEntity));
        }

        $this->postAttachmentRepository->savePostAttachment($postAttachmentEntity);

        return $postAttachmentEntity;
    }

    private function factoryAttachmentType(string $tmpFile): AttachmentType {
        if(ImageAttachmentType::detect($tmpFile)) {
            return new ImageAttachmentType();
        }else if(WebmAttachmentType::detect($tmpFile)) {
            return new WebmAttachmentType();
        }else{
            return new GenericFileAttachmentType();
        }
    }

    private function validateFileSize(string $tmpFile, FileAttachmentType $attachmentType) {
        $fileSize = filesize($tmpFile);

        if($fileSize > $attachmentType->getMaxFileSizeBytes()) {
            throw new FileTooBigException(sprintf('File should be less than %d bytes', $attachmentType->getMaxFileSizeBytes()));
        }else if ($fileSize < $attachmentType->getMinFileSizeBytes()){
            throw new FileTooSmallException(sprintf('File should be more than %d bytes', $attachmentType->getMinFileSizeBytes()));
        }
    }
}