<?php

namespace App\Command;

use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateResourceCommand extends Command
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
            ->setName('game:create-resources')
            ->setDescription('Creates Resources')
            ->setHelp('This command create a Resources')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Resources Creator',
            '==============',
            '',
        ]);

        $names = [
            [
                'name' => 'Viande',
                'price' => 4,
            ],
            [
                'name' => 'Légumes',
                'price' => 4,
            ],
            [
                'name' => 'Fruits',
                'price' => 4,
            ],
            [
                'name' => 'Pain',
                'price' => 8,
            ],
            [
                'name' => 'Lait',
                'price' => 8,
            ],
            [
                'name' => 'Miel',
                'price' => 6,
            ],
            [
                'name' => 'Épices',
                'price' => 20,
            ],
            [
                'name' => 'Fromage',
                'price' => 40,
            ],
            [
                'name' => 'Bougies',
                'price' => 40,
            ],
            [
                'name' => 'Bouteilles de vin',
                'price' => 40,
            ],
            [
                'name' => 'Chevaux',
                'price' => 1500,
            ],
            [
                'name' => 'Armes',
                'price' => 180,
            ],
            [
                'name' => 'Arcs',
                'price' => 100,
            ],
            [
                'name' => 'Armures',
                'price' => 140,
            ],
            [
                'name' => 'Plantes Médicinales',
                'price' => 4,
            ],
            [
                'name' => 'Cire d\'Abeilles',
                'price' => 4,
            ],
            [
                'name' => 'Vaches',
                'price' => 1000,
            ],
            [
                'name' => 'Raisin',
                'price' => 4,
            ],
            [
                'name' => 'Blé',
                'price' => 4,
            ],
            [
                'name' => 'Farine',
                'price' => 10,
            ],
            [
                'name' => 'Fer',
                'price' => 6,
            ],
            [
                'name' => 'Potions de Soin',
                'price' => 20,
            ],
            [
                'name' => 'Pierre',
                'price' => 4,
            ],
            [
                'name' => 'Bois',
                'price' => 2,
            ],
            [
                'name' => 'Foin',
                'price' => 4,
            ],
        ];

        foreach ($names as $name) {
            $resource = new Resource();
            $resource->setName($name['name']);
            $resource->setPrice($name['price']);

            $this->em->persist($resource);
        }

        $this->em->flush();

        $output->writeln('Les resources (nom, prix) ont bien été enregistrées !');
    }
}
