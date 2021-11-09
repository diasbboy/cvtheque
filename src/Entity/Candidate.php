<?php

namespace App\Entity;

use DateTime;
use App\Entity\Cv;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidateRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @UniqueEntity("email", message="L'adresse email est déjà utilisé")
 * @ORM\Entity(repositoryClass=CandidateRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
#[ApiResource(
    normalizationContext: ["groups" => ["user:read"]],
    denormalizationContext: ["groups" => ["user:write"]],
    paginationItemsPerPage:20,    
),
ApiFilter(
    SearchFilter::class, properties: [
        'email' => 'partial',
        'name' => 'partial', 
        'firstName' => 'partial',
        'city' => 'partial',
        'zip' => 'partial' 
    ]
)
]
class Candidate implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["user:read"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message="Le format de l'adresse email doit être valide")
     */
    #[
        Groups(["user:read", "user:write" ]),
        NotBlank(message:"L'adresse mail est obligatoire"),
    ]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(["user:read"])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @SerializedName("password")
     */
    #[
        Groups(["user:write" ]),
        Length(min:8, minMessage:"Le mot de passe doit contenir 6 caractères minimum"),
        NotBlank(message:"Le mot de passe est obligatoire")
    ]
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le nom est obligatoire")
     */
    #[
        Groups(["user:read", "user:write"]),
        Length(min: 2, minMessage:"Le nom doit contenir minimum 2 caractères"),
        NotBlank(message:"Le nom est obligatoire")
    ]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le prénom est obligatoire")
     * @Assert\Length(
     *      min=3, minMessage="Le prénom doit faire minimum {{ limit }} caractères",
     *      max=30, maxMessage="Le prénom doit faire maximum {{ limit }} caractères"
     * )
     */
    #[
        Groups(["user:read", "user:write"]),
        Length(min: 2, minMessage:"Le prénom doit contenir minimum 2 caractères"),
        NotBlank(message:"Le prénom est obligatoire")
    ]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $phone;

    /**
     * @ORM\Column(type="string", length=255,)
     */
    #[Groups(["user:read", "user:write"])]
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $seekingJobType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $seekingJobContract;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write"])]
    private $availability;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(["user:read"])]
    private $registrationDate;

    /**
     * @ORM\OneToOne(targetEntity=Cv::class, cascade={"persist", "remove"})
     */
    #[Groups(["user:read", "user:write"])]
    private $cv;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if(empty($this->registrationDate))
        {
            $this->registrationDate = new DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getSeekingJobType(): ?string
    {
        return $this->seekingJobType;
    }

    public function setSeekingJobType(string $seekingJobType): self
    {
        $this->seekingJobType = $seekingJobType;

        return $this;
    }

    public function getSeekingJobContract(): ?string
    {
        return $this->seekingJobContract;
    }

    public function setSeekingJobContract(string $seekingJobContract): self
    {
        $this->seekingJobContract = $seekingJobContract;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
