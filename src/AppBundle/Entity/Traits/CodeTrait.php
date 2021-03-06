<?php

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\AbstractDocumentClient;
use AppBundle\Entity\Facture;

/**
 * Trait CodeTrait
 * @package AppBundle\Entity\Traits
 */
trait CodeTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=16, nullable=false)
     */
    private $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return CodeTrait
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}