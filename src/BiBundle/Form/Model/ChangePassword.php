<?php
/**
 * @package    BiBundle\Form\Model
 * @author     zinnurov
 * @version    $Id: $
 */

namespace BiBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword extends BaseModel
{

    /**
     * @Assert\NotBlank()
     */
    private $oldPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Пароль должен содержать минимум {{ limit }} символов"
     * )
     * @Assert\Regex(
     *     pattern="/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{8,}$/",
     *     message = "Пароль должен содержать минимум одну строчную латинскую букву, одну заглавную и одну цифру"
     * )
     */
    private $password;

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
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