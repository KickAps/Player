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

    #[ORM\Column(type: 'string', length: 255)]
    private $onedrive_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $onedrive_authkey;

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

    public function getOnedriveId(): ?string
    {
        return $this->onedrive_id;
    }

    public function setOnedriveId(string $onedrive_id): self
    {
        $this->onedrive_id = $onedrive_id;

        return $this;
    }

    public function getOnedriveAuthkey(): ?string
    {
        return $this->onedrive_authkey;
    }

    public function setOnedriveAuthkey(string $onedrive_authkey): self
    {
        $this->onedrive_authkey = $onedrive_authkey;

        return $this;
    }
}
