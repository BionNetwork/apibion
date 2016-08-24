<?php
/**
 * @package    BiBundle\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use BiBundle\Security\Acl\Resource\Attribute\AbstractAttribute;
use BiBundle\Security\Acl\Resource\Attribute\AttributeFactory;
use BiBundle\Security\Acl\Resource\Attribute\AttributeInterface;

/**
 * Abstract resource with set of attributes
 */
abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var ArrayCollection
     */
    protected $attributes;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getResourceId();
    }

    public function __construct(array $attributes = array())
    {
        $this->attributes = new ArrayCollection();
        if (!empty($attributes)) {
            $this->setAttributesFromArray($attributes);
        } else {
            $this->init();
        }
    }

    /**
     * Return attributes
     *
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Return attributes as array
     *
     * @return array
     */
    public function getAttributesAsArray()
    {
        $data = [];
        /** @var AbstractAttribute $attribute */
        foreach ($this->getAttributes() as $attribute) {
            $data[] = $attribute->toArray();
        }
        return $data;
    }

    /**
     * Attribute names
     *
     * @return array
     */
    public function getAttributeNames()
    {
        $data = [];
        /** @var AttributeInterface $attribute */
        foreach ($this->getAttributes() as $attribute) {
            $data[] = $attribute->getName();
        }
        return $data;
    }

    /**
     * Initialize resource with default attributes
     * This method is used to simplify creation of resource that already has some attributes within it
     * If resource is loaded from external storage, this method is never called
     * It is used for new resources only!
     *
     * @return mixed
     */
    abstract protected function init();

    /**
     * Add attribute
     *
     * @param AttributeInterface $attribute
     */
    public function addAttribute(AttributeInterface $attribute)
    {
        $this->attributes->add($attribute);
    }

    /**
     * Check if attribute with given name exists
     *
     * @param $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->getAttributes()->exists(
            function($key, $element) use ($name){
                /** @var AttributeInterface $element */
                return $element->getName() == $name;
            });
    }

    /**
     * @param $name
     * @return AbstractAttribute|false
     */
    public function getAttribute($name)
    {
        $collection = $this->getAttributes()->filter(function($attribute) use ($name){
            /** @var AttributeInterface $attribute */
            return $name == $attribute->getName();
        });

        return $collection->current();
    }

    /**
     * Remove attribute
     *
     * @param AttributeInterface $attribute
     */
    public function removeAttribute(AttributeInterface $attribute)
    {
        $this->attributes->removeElement($attribute);
    }

    /**
     * Set resource attributes from array
     *
     * @param $attributes
     */
    protected function setAttributesFromArray(array $attributes)
    {
        foreach ($attributes as &$attribute) {
            if (isset($attribute['attribute'])) {
                $attribute['name'] = $attribute['attribute'];
                unset($attribute['attribute']);
                $item = AttributeFactory::factory($this->getResourceId(), $attribute);
                $this->addAttribute($item);
            }
        }
    }
}