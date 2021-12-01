<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @ORM\Table(name="tasks")
 */
class Task
{
    const STATUS_RECEIVED = 'received';
    const STATUS_READY    = 'ready';
    const STATUS_WAIT     = 'wait';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $userPhoto;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $userPhotoExtension;

    /**
     * @var string
     * @Groups({"Default"})
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $status;

    /**
     * @var string
     * @Groups({"Default"})
     * @ORM\Column(type="string", length=1000,nullable=true)
     */
    private $result;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserPhoto(): string
    {
        return $this->userPhoto;
    }

    public function setUserPhoto(string $userPhoto): self
    {
        $this->userPhoto = $userPhoto;

        return $this;
    }

    public function getUserPhotoExtension(): ?string
    {
        return $this->userPhotoExtension;
    }

    public function setUserPhotoExtension(string $userPhotoExtension): self
    {
        $this->userPhotoExtension = $userPhotoExtension;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }
}
