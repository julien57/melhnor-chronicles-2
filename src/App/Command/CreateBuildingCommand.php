<?php

namespace App\Command;

use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command create the resources produced and needed for each building
 * If 'isRequire' is true, call 'resourcesRequired' to get id of each required resource
 * If 'isProduction' is true, call 'resourcesProd' to get id of each produced resource
 */
class CreateBuildingCommand extends Command
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
            ->setName('game:create-buildings')
            ->setDescription('Creates Buildings')
            ->setHelp('This command create Buildings')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buildings = [
            [
                'name' => 'Pavillon de Chasse',
                'description' => 'Les chasseurs traquent les animaux de la région pour leur viande et leur peau.',
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [1],
            ],
            [
                'name' => 'Potager',
                'description' => 'Les potagers permettent de cultiver à des fins alimentaires des légumes sur de vastes étendues fertiles.',
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [2],
            ],
            [
                'name' => 'Plantation',
                'description' => 'La plantation est une exploitation agricole qui récolte des fruits de saison.',
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [3, 7, 15],
            ],
            [
                'name' => 'Boulangerie',
                'description' => 'La boulangerie est spécialisée dans la fabrication de toutes sortes de pain.',
                'isRequired' => true,
                'resourcesRequired' => [20],
                'isProduction' => true,
                'resourcesProd' => [4],
            ],
            [
                'name' => 'Exploitation Laitière',
                'description' => "L'exploitation laitière permet de produire du lait de vache.",
                'isRequired' => true,
                'resourcesRequired' => [17],
                'isProduction' => true,
                'resourcesProd' => [5],
            ],
            [
                'name' => 'Fromagerie',
                'description' => 'La fromagerie permet de produire du fromage à partir du lait.',
                'isRequired' => true,
                'resourcesRequired' => [5],
                'isProduction' => true,
                'resourcesProd' => [8],
            ],
            [
                'name' => 'Fabrique de Bougies',
                'description' => "La fabrique permet de produire des bougies, seul moyen de s'éclairer lors des longues nuits melhnoriennes.",
                'isRequired' => true,
                'resourcesRequired' => [16],
                'isProduction' => true,
                'resourcesProd' => [9],
            ],
            [
                'name' => 'Domaine Viticole',
                'description' => 'Le savoir-faire du domaine viticole permet de produire et de mettre en bouteille le vin.',
                'isRequired' => true,
                'resourcesRequired' => [18],
                'isProduction' => true,
                'resourcesProd' => [10],
            ],
            [
                'name' => 'Ruches',
                'description' => "Les ruches permettent d'élever des abeilles produisant de la cire et du miel.",
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [16, 6],
            ],
            [
                'name' => 'Ferme',
                'description' => "Les fermes permettent d'élever vaches.",
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [17],
            ],
            [
                'name' => 'Vigne',
                'description' => 'Les vignes sont une exploitation agricole de raisins destinées à la production de vins.',
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [18],
            ],
            [
                'name' => 'Champs de Blé',
                'description' => 'Les champs de blé cultivent les céréales à but alimentaire sur de vastes étendues fertiles.',
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [19, 25],
            ],
            [
                'name' => 'Moulin',
                'description' => 'Cette infrastructure permet de moudre efficacement le grain de blé en farine.',
                'isRequired' => true,
                'resourcesRequired' => [19],
                'isProduction' => true,
                'resourcesProd' => [20],
            ],
            [
                'name' => 'Mine de Fer',
                'description' => 'Cette mine exploite les gisements géologiques souterrains en extrayant le minerai de fer du sol.',
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [21],
            ],
            [
                'name' => 'Écurie',
                'description' => "L'écurie permet de produire des chevaux et donne la possibilité de former des cavaliers.",
                'isRequired' => true,
                'resourcesRequired' => [25],
                'isProduction' => true,
                'resourcesProd' => [11],
            ],
            [
                'name' => 'Forge',
                'description' => "Dans cette atelier on y travaille le métal afin d’en faire les meilleures armes pour l'armée du royaume.",
                'isRequired' => true,
                'resourcesRequired' => [21],
                'isProduction' => true,
                'resourcesProd' => [12],
            ],
            [
                'name' => 'Armurerie',
                'description' => "Dans cette atelier on y travaille le métal et le bois afin d'en faire de puissantes armures et arcs.",
                'isRequired' => true,
                'resourcesRequired' => [24, 21],
                'isProduction' => true,
                'resourcesProd' => [13, 14],
            ],
            [
                'name' => 'Apothicaire',
                'description' => 'Ses connaissances de la flore lui permettent de produire des potions de soin de toutes sortes.',
                'isRequired' => true,
                'resourcesRequired' => [15],
                'isProduction' => true,
                'resourcesProd' => [22],
            ],
            [
                'name' => 'Carrière de Pierre',
                'description' => "On y taille d'imposants blocs de pierre destinés à la construction de bâtiments.",
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [23],
            ],
            [
                'name' => 'Camp de Bûcheron',
                'description' => "Les bûcherons sont des spécialistes de l'abattage et de la coupe des arbres.",
                'isRequired' => false,
                'isProduction' => true,
                'resourcesProd' => [24],
            ],
        ];

        foreach ($buildings as $buildingConfig) {
            $building = new Building();
            $building->setName($buildingConfig['name']);
            $building->setDescription($buildingConfig['description']);

            if ($buildingConfig['isProduction']) {
                $resourcesProd = $this->em->getRepository(Resource::class)->findById($buildingConfig['resourcesProd']);

                foreach ($resourcesProd as $resource) {
                    $buildingProduction = new BuildingResource();
                    $buildingProduction->setIsProduction(true);
                    $buildingProduction->setBuilding($building);
                    $buildingProduction->setResource($resource);

                    $this->em->persist($buildingProduction);
                }

                if ($buildingConfig['isRequired']) {
                    $resourcesRequired = $this->em->getRepository(Resource::class)->findById($buildingConfig['resourcesRequired']);

                    $buildingRequired = new BuildingResource();
                    foreach ($resourcesRequired as $resourceConfig) {
                        $buildingRequired->setIsRequired(true);
                        $buildingRequired->setBuilding($building);
                        $buildingRequired->setResource($resourceConfig);

                        $this->em->persist($buildingRequired);
                    }
                }
            }
        }

        $buildingsArmy = [
            [
                'name' => 'Caserne',
                'description' => 'Permet de former des soldats.',
            ],
            [
                'name' => 'Archerie',
                'description' => 'Permet de former des archers.',
            ],
            [
                'name' => 'Port',
                'description' => 'Permet de construire des navires de transport.',
            ],
        ];

        foreach ($buildingsArmy as $buildingArmy) {
            $building = new Building();
            $building->setName($buildingArmy['name']);
            $building->setDescription($buildingArmy['description']);

            $this->em->persist($building);
        }

        $this->em->flush();

        $output->writeln('The buildings associed with resources have been created !');
    }
}
