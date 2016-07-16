<?php
namespace Domain\IM\Entity;

use Application\Util\JSONSerializable;
use Domain\Profile\Entity\Profile;
use MongoDB\BSON\ObjectID;

class Message implements JSONSerializable
{
    /** @var ObjectID */
    private $id;

    /** @var Profile */
    private $author;

    /** @var \DateTime */
    private $dateCreated;

    /** @var string */
    private $content;

    /** @var MessageReadStatus */
    private $readStatus;

    /** @var int[] */
    private $attachmentIds = [];

    public function __construct(Profile $author, string $content, array $attachmentIds)
    {
        $this->author = $author;
        $this->content = $content;
        $this->attachmentIds = $attachmentIds;
        $this->dateCreated = new \DateTime();
    }

    public function toJSON(): array
    {
        $result = $this->toMongoBSON();

        if($this->hasId()) {
            $result['_id'] = (string) $this->getId();
        }

        return $result;
    }

    public function toMongoBSON(): array
    {
        return [
            'author_id' => $this->author->getId(),
            'content' => $this->content,
            'read_status' => $this->readStatus->toMongoBSON(),
            'attachment_ids' => $this->attachmentIds,
            'date_created' => $this->dateCreated->format(\DateTime::RFC2822),
            'date_created_obj' => $this->dateCreated,
        ];
    }

    public function getAuthor(): Profile
    {
        return $this->author;
    }

    public function hasId(): bool
    {
        return $this->id instanceof ObjectID;
    }

    public function getId(): ObjectID
    {
        return $this->id;
    }

    public function specifyId(ObjectID $id)
    {
        if($this->id !== null) {
            throw new \Exception('ID is already specified');
        }

        $this->id = $id;
    }

    public function getReadStatus(): MessageReadStatus
    {
        return $this->readStatus;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAttachmentIds(): array
    {
        return $this->attachmentIds;
    }
}