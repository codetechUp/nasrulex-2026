<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
 
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *@UniqueEntity(
* fields={"email"},
 * 
 *     message=" Email est deja utiliser.")
 */

class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *@Assert\Email
     */
    private $email;

   
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="8", minMessage="le  mode de pass de doit etre superrieur à 8 caractere")
     */
    private $password;
  


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;
  /**
   * @Assert\EqualTo(propertyPath="password" , message="Tapez le meme mot de passe pou confirmé")
   */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role="ROLE_USER";

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_S;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone_S;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse_S;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numero_I_F;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subscription", mappedBy="user")
     */
    private $subscriptions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ActivationToken;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
      if($this->role==null){
          return["ROLE_USER"];
      }
     return[$this->role];

    }

   

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNomS(): ?string
    {
        return $this->nom_S;
    }

    public function setNomS(string $nom_S): self
    {
        $this->nom_S = $nom_S;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTelephoneS(): ?string
    {
        return $this->telephone_S;
    }

    public function setTelephoneS(string $telephone_S): self
    {
        $this->telephone_S = $telephone_S;

        return $this;
    }

    public function getAdresseS(): ?string
    {
        return $this->adresse_S;
    }

    public function setAdresseS(string $adresse_S): self
    {
        $this->adresse_S = $adresse_S;

        return $this;
    }

    public function getNumeroIF(): ?string
    {
        return $this->numero_I_F;
    }

    public function setNumeroIF(string $numero_I_F): self
    {
        $this->numero_I_F = $numero_I_F;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->ActivationToken;
    }

    public function setActivationToken(?string $ActivationToken): self
    {
        $this->ActivationToken = $ActivationToken;

        return $this;
    }
    public function isAccountNonExpired(){
        return true ;
    }
    public function isAccountNonLocked(){
        return true ;
    }
    public function isCredentialsNonExpired(){
        return true ;
    }
    public function isEnabled(){
        if($this->ActivationToken == null ){
            return true ;
        }
        
    }
   

  
}
