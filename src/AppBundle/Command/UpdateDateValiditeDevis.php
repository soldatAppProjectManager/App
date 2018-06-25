<?php
/**
 * Created by PhpStorm.
 * User: FOFANA
 * Date: 25/06/2018
 * Time: 15:50
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDateValiditeDevis extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('devis:update-date')
            ->setDescription('Updates devis Date Fin de Validité');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $devis = $em->getRepository('AppBundle:Devis')->findAll();

        foreach ($devis as $chaque_devis) {
            /** @var \AppBundle\Entity\Devis $chaque_devis */
            $chaque_devis->updateDateFinValidite();
        }
        $em->flush();
        $output->writeln('Les champs ont correctement été mis à jour!');
    }
}
