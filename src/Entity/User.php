<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity("email", message="Cette adresse mail est déjà utilisée")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", inversedBy="users", cascade={"persist"})
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="createdBy")
     */
    private $createdIssues;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="assignedTo")
     */
    private $assignedIssues;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->createdIssues = new ArrayCollection();
        $this->assignedIssues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $token): self
    {
        $this->confirmationToken = $token;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return 'gaia';
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
        }

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getCreatedIssues(): Collection
    {
        return $this->createdIssues;
    }

    public function addCreatedIssue(Issue $issue): self
    {
        if (!$this->createdIssues->contains($issue)) {
            $this->createdIssues[] = $issue;
            $issue->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedIssue(Issue $issue): self
    {
        if ($this->createdIssues->contains($issue)) {
            $this->createdIssues->removeElement($issue);
            // set the owning side to null (unless already changed)
            if ($issue->getCreatedBy() === $this) {
                $issue->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getAssignedIssues(): Collection
    {
        return $this->assignedIssues;
    }

    public function addAssignedIssue(Issue $assignedIssue): self
    {
        if (!$this->assignedIssues->contains($assignedIssue)) {
            $this->assignedIssues[] = $assignedIssue;
            $assignedIssue->setAssignedTo($this);
        }

        return $this;
    }

    public function removeAssignedIssue(Issue $assignedIssue): self
    {
        if ($this->assignedIssues->contains($assignedIssue)) {
            $this->assignedIssues->removeElement($assignedIssue);
            // set the owning side to null (unless already changed)
            if ($assignedIssue->getAssignedTo() === $this) {
                $assignedIssue->setAssignedTo(null);
            }
        }

        return $this;
    }
}
