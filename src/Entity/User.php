<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table("User")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable
 */
class User extends BaseUser
{
    const ROLE_EMPLEADO    = 'ROLE_EMPLEADO';
    const ROLE_AUDITOR     = 'ROLE_AUDITOR';
    const ROLE_JUEZ_TSJ    = 'ROLE_JUEZ_TSJ';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static $rolesUser = [
      'EMPLEADO'            => self::ROLE_EMPLEADO,
      'AUDITOR'             => self::ROLE_AUDITOR,
      'JUEZ TSJ'            => self::ROLE_JUEZ_TSJ,
      'SUPER ADMINISTRADOR' => self::ROLE_SUPER_ADMIN,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", nullable=true, length=255)
     * @Gedmo\Versioned
     * @Assert\NotBlank(message="Por favor ingrese su nombre.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "El nombre es demasiado corto.",
     *      maxMessage = "El nombre es demasiado largo.",
     *      groups={"Registration", "Profile"}
     * )
     *
     */
    protected $name;

    /**
     * @var string $lastName
     * @ORM\Column(name="lastName", type="string", nullable=true, length=255)
     * @Gedmo\Versioned
     * @Assert\NotBlank(message="Por favor ingrese su apellido.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "El apellido es demasiado corto.",
     *      maxMessage = "El apellido es demasiado largo.",
     *      groups={"Registration", "Profile"}
     * )
     *
     */
    protected $lastName;

    /**
     * @var string $telephone
     * @Gedmo\Versioned
     * @ORM\Column(name="telephone", type="string", nullable=true, length=255)
     */
    protected $telephone;

    /**
     * @var string $celular
     * @Gedmo\Versioned
     * @ORM\Column(name="cellphone", type="string", nullable=true, length=255)
     */
    protected $cellphone;

    private $superAdmin;
    private $group;

    public function __construct() {
        parent::__construct();
        // your own logic
    }

    public function __toString() {
      return $this->getLastName().", ".$this->getName();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->name = trim(strtoupper($this->name));
        $this->lastName = trim(strtoupper($this->lastName));
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->name = trim(strtoupper($this->name));
        $this->lastName = trim(strtoupper($this->lastName));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCellphone(): ?string
    {
        return $this->cellphone;
    }

    public function setCellphone(?string $cellphone): self
    {
        $this->cellphone = $cellphone;

        return $this;
    }
}
