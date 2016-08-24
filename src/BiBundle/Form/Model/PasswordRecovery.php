<?php
/**
 * @package    BiBundle\Form\Model
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form\Model;

class PasswordRecovery extends BaseModel implements TokenInterface
{
    /**
     * @Assert\NotBlank(groups={"flow_password_recovery_step1", "flow_password_recovery_step2"})
     * @Assert\Regex(groups={"flow_password_recovery_step1", "flow_password_recovery_step2"},
     *     pattern="/^\+?([0-9]{11})$/", message="Формат телефона должен быть +7(999)9999999"
     * )
     */
    private $phone;

    /**
     * @Assert\NotBlank(groups={"flow_password_recovery_step2"})
     */
    private $smsCode;
    /**
     * @Assert\NotBlank(groups={"flow_password_recovery_step3"})
     */
    private $password;

    private $token;

    /**
     * Get token
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param mixed $token
     * @return mixed|void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getSmsCode()
    {
        return $this->smsCode;
    }

    /**
     * @param mixed $smsCode
     */
    public function setSmsCode($smsCode)
    {
        $this->smsCode = $smsCode;
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

}