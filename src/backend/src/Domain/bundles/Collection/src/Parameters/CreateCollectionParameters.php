<?php
namespace Domain\Collection\Parameters;

class CreateCollectionParameters
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var int|null */
    private $themeId;

    public function __construct(string $title, string $description, int $themeId = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->themeId = $themeId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThemeId(): int
    {
        return $this->themeId;
    }

    public function hasThemeId(): bool
    {
        return $this->themeId !== null;
    }
}