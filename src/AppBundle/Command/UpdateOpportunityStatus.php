<?php
/**
 * Created by PhpStorm.
 * User: FOFANA
 * Date: 22/06/2018
 * Time: 15:59
 */

namespace AppBundle\Command;


use AppBundle\Entity\Opportunity;
use AppBundle\Entity\OpportunityStatus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateOpportunityStatus extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:update-status')
            ->setDescription('Updates opportunities statuses')
            ->setHelp('This command allows you to update the opportunities statuses...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine');

        $opportunities = $em->getRepository('AppBundle:Opportunity')
            ->findAll();

        foreach ($opportunities as $opportunity) {
            /** @var Opportunity $opportunity */
            if ($opportunity->getDateEcheance() < new \DateTime('now')){
                $opportunity->setStatus($em->getRepository('AppBundle:OpportunityStatus')
                    ->findOneBy(['code' => OpportunityStatus::ECHU_CODE]));
            }
        }

        $output->writeln('Les statuts ont été correctement mises à jour!');
    }
}
