<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute;

use Doctrine\Common\Collections\ArrayCollection;
use BiBundle\Security\Acl\Resource\Attribute\Grant\Grant;
use BiBundle\Security\Acl\Resource\Attribute\Grant\GrantInterface;
use BiBundle\Security\Acl\Role\RoleFactory;

abstract class AbstractAttribute implements AttributeInterface
{
    /**
     * Attribute short description
     *
     * @var string
     */
    protected $text;
    /**
     * Attribute full description
     *
     * @var string
     */
    protected $description;
    /**
     * Attribute type (bool for default)
     *
     * @var string
     */
    protected $type = 'bool';
    /**
     * Default value for attribute
     *
     * @var mixed
     */
    protected $default = false;

    /**
     * @var ArrayCollection
     */
    protected $grants;

    public function __construct()
    {
        $this->grants = new ArrayCollection();
    }

    /**
     * Return attribute as array
     *
     * @return array
     */
    final public function toArray()
    {
        return [
            'attribute' => $this->getName(),
            'text' => $this->getText(),
            'description' => $this->getDescription(),
            'type' => $this->getType(),
            'default' => $this->getDefault(),
            'grants' => $this->getGrantsAsArray()
        ];
    }

    /**
     * Set attribute data from array
     *
     * @param array $data
     */
    public function fromArray(array $data)
    {
        foreach (array('text', 'description', 'type', 'default') as $key) {
            if (isset($data[$key])) {
                $this->{$key} = $data[$key];
            }
        }
        if (isset($data['grants'])) {
            $grants = [];
            foreach ($data['grants'] as $grant) {
                $grants[] = new Grant(RoleFactory::getRole($grant['role']), (bool)$grant['grant']);
            }
            $this->setGrants($grants);
        }
    }
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return ArrayCollection
     */
    public function getGrants()
    {
        return $this->grants;
    }

    /**
     * Return grants as array
     *
     * @return array
     */
    public function getGrantsAsArray()
    {
        $data = [];
        foreach ($this->getGrants() as $grant) {
            $data[] = $grant->toArray();
        }
        return $data;
    }

    /**
     * @param array $grants
     */
    public function setGrants(array $grants)
    {
        // clear grants
        $this->grants = new ArrayCollection();
        // set new grants
        foreach ($grants as $grant) {
            $this->addGrant($grant);
        }
    }

    /**
     * Get grant for selected role
     *
     * @param $role
     * @return GrantInterface|false
     */
    public function getGrant($role)
    {
        $collection = $this->getGrants()->filter(function($grant) use ($role){
            /** @var GrantInterface $grant */
            return strncasecmp($role, $grant->getRole()->getRole(), strlen($role)) === 0;
        });

        return $collection->current();
    }

    /**
     * Add grant
     *
     * @param GrantInterface $grant
     */
    public function addGrant(GrantInterface $grant)
    {
        $this->grants->add($grant);
    }

    /**
     * Remove grant
     *
     * @param GrantInterface $grant
     */
    public function removeGrant(GrantInterface $grant)
    {
        $this->grants->removeElement($grant);
    }
}