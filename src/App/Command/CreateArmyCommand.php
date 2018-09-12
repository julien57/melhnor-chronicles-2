<?php

namespace App\Command;

use App\Entity\Army;
use App\Entity\Avatar;
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

        // Create Army

        $armys = [
            [
                'name' => 'Soldats',
                'img' => 'horde62.jpg',
                'power' => 7,
                'life' => 10
            ],
            [
                'name' => 'Archers',
                'img' => 'redempteurs15.jpg',
                'power' => 5,
                'life' => 8
            ],
            [
                'name' => 'Cavaliers',
                'img' => 'horde58.jpg',
                'power' => 10,
                'life' => 15
            ],
            [
                'name' => 'Navires de guerre',
                'img' => 'transport.jpg',
                'power' => 13,
                'life' => 20
            ]
        ];

        foreach ($armys as $armyConfig) {
            $army = new Army();
            $army->setName($armyConfig['name']);
            $army->setImg($armyConfig['img']);
            $army->setPower($armyConfig['power']);

            $this->em->persist($army);
        }

        $this->em->flush();

        $output->writeln('Les armées ont bien été enregistrées !');
    }
}