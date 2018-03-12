<?php

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\AbstractDocumentClient;
use AppBundle\Entity\Facture;

/**
 * Class CreatedByTrait
 * @package CoreBundle\Entity\Traits
 */
trait GenerateRefTrait
{

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=16, nullable=true)
     */
    private $reference;

    /**
     * @var int
     *
     * @ORM\Column(name="uuid", type="integer")
     */
    private $uuid;


    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Facture
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set uuid
     *
     * @param integer $uuid
     *
     * @return Facture
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return integer
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param $nextUuid
     */
    public function generateRef($nextUuid)
    {
        $this->setUuid($nextUuid);
        $ref = sprintf(
            self::REF_FORMAT,
            str_repeat('0', AbstractDocumentClient::NBR_ZERO_IN_REFERENCE - strlen((string)$this->getUuid())),
            $this->getUuid(),
            date('Y'));
        $this->setReference($ref);
    }
}