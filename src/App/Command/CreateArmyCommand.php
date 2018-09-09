<?php

namespace App\Command;

use App\Entity\Army;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateArmyCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('game:create-army')
            ->setDescription('Creates Armys')
            ->setHelp('This command create armys name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Army Creator',
            '==============',
            '',
        ]);

        $armys = ['Soldats', 'Archers', 'Cavaliers', 'Navires de guerre'];

        foreach ($armys as $armyConfig) {
            $army = new Army();
            $army->setName($armyConfig);

            $this->em->persist($army);
        }

        $this->em->flush();

        $output->writeln('Les armées ont bien été enregistrées !');
    }
}