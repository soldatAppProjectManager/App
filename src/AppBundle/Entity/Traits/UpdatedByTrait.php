<?php

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait UpdatedByTrait
 * @package CoreBundle\Entity\Traits
 */
trait UpdatedByTrait
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Blameable(on="update")
     */
    private $updatedBy;

    /**
     * @return User
     */
    public function getupdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param User $updatedBy
     * @return $this
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}