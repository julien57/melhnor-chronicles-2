<?php

namespace App\Command;

use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command create name of resource, his price for market.
 * If 'isFood' is true, is used to feed the population in production
 */
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
                'isFood' => true,
            ],
            [
                'name' => 'Légumes',
                'price' => 4,
                'isFood' => true,
            ],
            [
                'name' => 'Fruits',
                'price' => 4,
                'isFood' => true,
            ],
            [
                'name' => 'Pain',
                'price' => 8,
                'isFood' => true,
            ],
            [
                'name' => 'Lait',
                'price' => 8,
                'isFood' => true,
            ],
            [
                'name' => 'Miel',
                'price' => 6,
                'isFood' => true,
            ],
            [
                'name' => 'Épices',
                'price' => 20,
                'isFood' => true,
            ],
            [
                'name' => 'Fromage',
                'price' => 40,
                'isFood' => true,
            ],
            [
                'name' => 'Bougies',
                'price' => 40,
                'isFood' => true,
            ],
            [
                'name' => 'Bouteilles de vin',
                'price' => 40,
                'isFood' => true,
            ],
            [
                'name' => 'Chevaux',
                'price' => 1500,
                'isFood' => false,
            ],
            [
                'name' => 'Armes',
                'price' => 180,
                'isFood' => false,
            ],
            [
                'name' => 'Arcs',
                'price' => 100,
                'isFood' => false,
            ],
            [
                'name' => 'Armures',
                'price' => 140,
                'isFood' => false,
            ],
            [
                'name' => 'Plantes Médicinales',
                'price' => 4,
                'isFood' => false,
            ],
            [
                'name' => 'Cire d\'Abeilles',
                'price' => 4,
                'isFood' => false,
            ],
            [
                'name' => 'Vaches',
                'price' => 1000,
                'isFood' => false,
            ],
            [
                'name' => 'Raisin',
                'price' => 4,
                'isFood' => false,
            ],
            [
                'name' => 'Blé',
                'price' => 4,
                'isFood' => false,
            ],
            [
                'name' => 'Farine',
                'price' => 10,
                'isFood' => false,
            ],
            [
                'name' => 'Fer',
                'price' => 6,
                'isFood' => false,
            ],
            [
                'name' => 'Potions de Soin',
                'price' => 20,
                'isFood' => false,
            ],
            [
                'name' => 'Pierre',
                'price' => 4,
                'isFood' => false,
            ],
            [
                'name' => 'Bois',
                'price' => 2,
                'isFood' => false,
            ],
            [
                'name' => 'Foin',
                'price' => 4,
                'isFood' => false,
            ],
        ];

        foreach ($names as $name) {
            $resource = new Resource();
            $resource->setName($name['name']);
            $resource->setPrice($name['price']);
            $resource->setIsFood($name['isFood']);

            $this->em->persist($resource);
        }

        $this->em->flush();

        $output->writeln('Les resources (nom, prix) ont bien été enregistrées !');
    }
}
