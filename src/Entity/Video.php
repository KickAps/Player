<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $year;

    #[ORM\Column(type: 'string', length: 255)]
    private $thumbnail;

    #[ORM\Column(type: 'array', nullable: true)]
    private $flag = [];

    #[ORM\Column(type: 'string', length: 255)]
    private $external_id;

    #[ORM\Column(type: 'string', length: 2000, nullable: true)]
    private $external_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getFlag(): ?array
    {
        return $this->flag;
    }

    public function setFlag(?array $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(string $external_id): self
    {
        $this->external_id = $external_id;

        return $this;
    }


    public function getExternalUrl(): ?string
    {
        return $this->external_url;
    }

    public function setExternalUrl(?string $external_url): self
    {
        $this->external_url = $external_url;

        return $this;
    }
}
