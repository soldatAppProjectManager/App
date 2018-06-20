<?php
/**
 * Created by PhpStorm.
 * User: FOFANA
 * Date: 19/06/2018
 * Time: 16:15
 */

namespace AppBundle\Search;


class PeriodCriteria
{
    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    public function __construct()
    {
        $this->startDate = new \DateTime('first day of this month');

        $this->endDate = new \DateTime('last day of this month');
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime|null $startDate
     * @return PeriodCriteria
     */
    public function setStartDate(\DateTime $startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime|null $endDate
     *
     * @return PeriodCriteria
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}