<?php

namespace App\Entity;

use App\Repository\DownloadFilesRepositoriesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DownloadFilesRepositoriesRepository::class)]
class DownloadFilesRepositories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
