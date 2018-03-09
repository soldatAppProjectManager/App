<?php

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class CreatedByTrait
 * @package CoreBundle\Entity\Traits
 */
trait CreatedByTrait
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Blameable(on="create")
     */
    private $createdBy;

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     * @return $this
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}