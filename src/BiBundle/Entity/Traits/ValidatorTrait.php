<?php
/**
 * @package    BiBundle\Entity\Trait
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Entity\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use BiBundle\Entity\Exception\ValidatorException;

trait ValidatorTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $value The value to validate
     * @param Constraint|Constraint[] $constraints The constraint(s) to validate
     *                                             against
     * @param array|null $groups The validation groups to
     *                                             validate. If none is given,
     *                                             "Default" is assumed
     * @return bool
     * @throws ValidatorException
     */
    public function validate($value, $constraints = null, $groups = null)
    {
        $validator = $this->getContainer()->get('validator');
        $result = $validator->validate($value, $constraints, $groups);
        if ($result->count() > 0) {
            // throw first exception in validation constraints
            /** @var \Symfony\Component\Validator\ConstraintViolation $validationConstraint */
            foreach ($result as $validationConstraint) {
                $messageTemplate = "Field <%s> has value <%s>. %s";
                $message = sprintf($messageTemplate,
                    $validationConstraint->getPropertyPath(),
                    $validationConstraint->getInvalidValue(),
                    $validationConstraint->getMessage());

                throw new ValidatorException($message);
            }
        }
        return true;
    }
}