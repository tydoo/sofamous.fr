<?php

namespace App\Entity;

use App\Repository\EmailTellDevelopperRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EmailTellDevelopperRepository::class)]
class EmailTellDevelopper {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct(
        string $email
    ) {
        $this->email = $email;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): static {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'created_at' => $this->createdAt->format('Y-m-d\TH:i:s\Z')
        ];
    }
}
