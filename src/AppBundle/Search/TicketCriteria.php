<?php

namespace AppBundle\Search;

use AppBundle\Entity\User;

class TicketCriteria {

    private $startDate;

    private $endDate;

    private $user;

    private $customer;

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime|null $startDate
     * @return TicketCriteria
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
     * @return TicketCriteria
     */
    public function setEndDate(\DateTime $endDate = null)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return TicketCriteria
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     * @return TicketCriteria
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }
}