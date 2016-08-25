<?php

namespace BiBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface, \Serializable
{

    /**
     * User roles
     *
     * @var array
     */
    private $roles = ['ROLE_USER'];

    /**
     * Add user role
     *
     * @param $role
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    /**
     * Возвращает контакты, не являющиеся дефолтными
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNonDefaultContacts()
    {
        return $this->contacts->filter(
            function ($entry) {
                /** @var \BiBundle\Entity\UserContact $entry */
                return false === $entry->getIsDefault();
            }
        );
    }

    /**
     * @param UserContact $contact
     * @return $this
     */
    public function addNonDefaultContact(\BiBundle\Entity\UserContact $contact)
    {
        $this->addContact($contact);
        return $this;
    }

    /**
     * @param UserContact $contact
     */
    public function removeNonDefaultContact(\BiBundle\Entity\UserContact $contact)
    {
        $this->removeContact($contact);
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $middlename;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTime
     */
    private $birthDate;

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var string
     */
    private $avatarSmall;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var \DateTime
     */
    private $lastLoginOn;

    /**
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var boolean
     */
    private $mailNotification;

    /**
     * @var boolean
     */
    private $mustChangePasswd;

    /**
     * @var \DateTime
     */
    private $passwdChangedOn;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var boolean
     */
    private $isBlocked = false;

    /**
     * @var boolean
     */
    private $isDeleted = false;

    /**
     * @var string
     */
    private $verifyEmailUuid;

    /**
     * @var boolean
     */
    private $isSuperuser;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $contacts;

    /**
     * @var \BiBundle\Entity\UserStatus
     */
    private $status;

    /**
     * @var \BiBundle\Entity\UserRole
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $purchase;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set middlename
     *
     * @param string $middlename
     *
     * @return User
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;

        return $this;
    }

    /**
     * Get middlename
     *
     * @return string
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatarSmall
     *
     * @param string $avatarSmall
     *
     * @return User
     */
    public function setAvatarSmall($avatarSmall)
    {
        $this->avatarSmall = $avatarSmall;

        return $this;
    }

    /**
     * Get avatarSmall
     *
     * @return string
     */
    public function getAvatarSmall()
    {
        return $this->avatarSmall;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set lastLoginOn
     *
     * @param \DateTime $lastLoginOn
     *
     * @return User
     */
    public function setLastLoginOn($lastLoginOn)
    {
        $this->lastLoginOn = $lastLoginOn;

        return $this;
    }

    /**
     * Get lastLoginOn
     *
     * @return \DateTime
     */
    public function getLastLoginOn()
    {
        return $this->lastLoginOn;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return User
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return User
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set mailNotification
     *
     * @param boolean $mailNotification
     *
     * @return User
     */
    public function setMailNotification($mailNotification)
    {
        $this->mailNotification = $mailNotification;

        return $this;
    }

    /**
     * Get mailNotification
     *
     * @return boolean
     */
    public function getMailNotification()
    {
        return $this->mailNotification;
    }

    /**
     * Set mustChangePasswd
     *
     * @param boolean $mustChangePasswd
     *
     * @return User
     */
    public function setMustChangePasswd($mustChangePasswd)
    {
        $this->mustChangePasswd = $mustChangePasswd;

        return $this;
    }

    /**
     * Get mustChangePasswd
     *
     * @return boolean
     */
    public function getMustChangePasswd()
    {
        return $this->mustChangePasswd;
    }

    /**
     * Set passwdChangedOn
     *
     * @param \DateTime $passwdChangedOn
     *
     * @return User
     */
    public function setPasswdChangedOn($passwdChangedOn)
    {
        $this->passwdChangedOn = $passwdChangedOn;

        return $this;
    }

    /**
     * Get passwdChangedOn
     *
     * @return \DateTime
     */
    public function getPasswdChangedOn()
    {
        return $this->passwdChangedOn;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isBlocked
     *
     * @param boolean $isBlocked
     *
     * @return User
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    /**
     * Get isBlocked
     *
     * @return boolean
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return User
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set verifyEmailUuid
     *
     * @param string $verifyEmailUuid
     *
     * @return User
     */
    public function setVerifyEmailUuid($verifyEmailUuid)
    {
        $this->verifyEmailUuid = $verifyEmailUuid;

        return $this;
    }

    /**
     * Get verifyEmailUuid
     *
     * @return string
     */
    public function getVerifyEmailUuid()
    {
        return $this->verifyEmailUuid;
    }

    /**
     * Set isSuperuser
     *
     * @param boolean $isSuperuser
     *
     * @return User
     */
    public function setIsSuperuser($isSuperuser)
    {
        $this->isSuperuser = $isSuperuser;

        return $this;
    }

    /**
     * Get isSuperuser
     *
     * @return boolean
     */
    public function getIsSuperuser()
    {
        return $this->isSuperuser;
    }

    /**
     * Add contact
     *
     * @param \BiBundle\Entity\UserContact $contact
     *
     * @return User
     */
    public function addContact(\BiBundle\Entity\UserContact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \BiBundle\Entity\UserContact $contact
     */
    public function removeContact(\BiBundle\Entity\UserContact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set status
     *
     * @param \BiBundle\Entity\UserStatus $status
     *
     * @return User
     */
    public function setStatus(\BiBundle\Entity\UserStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \BiBundle\Entity\UserStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set role
     *
     * @param \BiBundle\Entity\UserRole $role
     *
     * @return User
     */
    public function setRole(\BiBundle\Entity\UserRole $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \BiBundle\Entity\UserRole
     */
    public function getRole()
    {
        return $this->role;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->login,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->login,
            $this->password
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
    }

    /**
     * Add purchase
     *
     * @param \BiBundle\Entity\Purchase $purchase
     *
     * @return User
     */
    public function addPurchase(\BiBundle\Entity\Purchase $purchase)
    {
        $this->purchase[] = $purchase;

        return $this;
    }

    /**
     * Remove purchase
     *
     * @param \BiBundle\Entity\Purchase $purchase
     */
    public function removePurchase(\BiBundle\Entity\Purchase $purchase)
    {
        $this->purchase->removeElement($purchase);
    }

    /**
     * Get purchase
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPurchase()
    {
        return $this->purchase;
    }
}
