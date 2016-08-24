<?php
/**
 * @package    BiBundle\Form\Model
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Registration extends BaseModel implements TokenInterface
{
    /**
     * @Assert\NotBlank(groups={"flow_registration_step3"})
     */
    private $login;

    /**
     * @Assert\NotBlank(groups={"flow_registration_step3"})
     */
    private $firstName;

    /**
     * @Assert\NotBlank(groups={"flow_registration_step3"})
     */
    private $lastName;

    private $middleName;
    /**
     * @Assert\NotBlank(groups={"flow_registration_step3"})
     */

    private $password;


    private $token;

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param mixed $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return mixed|void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}