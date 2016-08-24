<?php
/**
 * Created by PhpStorm.
 * User: storageprocedure
 * Date: 07.06.2016
 * Time: 13:22
 */

namespace BiBundle\Form\Model\Issue;

use Symfony\Component\Validator\Constraints as Assert;
use BiBundle\Form\Model\BaseModel;

class CreateModel extends BaseModel
{

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $name;


    /**
     * @var \BiBundle\Entity\Project
     * @Assert\NotBlank()
     */
    private $project;

    /**
     * @var \BiBundle\Entity\Issue
     */
    private $parent;


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \BiBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param \BiBundle\Entity\Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return \BiBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param \BiBundle\Entity\Issue $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get issue model
     *
     * @return \BiBundle\Entity\Issue
     */
    public function getIssue()
    {
        $issue = new \BiBundle\Entity\Issue();
        $issue->setProject($this->getProject());
        $issue->setParent($this->getParent());
        $issue->setName($this->getName());

        return $issue;
    }
}