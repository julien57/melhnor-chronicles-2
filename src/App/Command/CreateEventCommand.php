<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate event "Dragon of the shadow" with different levels
 */
class CreateEventCommand extends Command
{
    const DRAGON_EASY_DIFFICULTY = 'Facile';
    const DRAGON_EASY_LIFE = 3000;
    const DRAGON_EASY_POWER = 60;
    const FLAMETHROWER_EASY_ATTACK = 80;
    const DRAGON_EASY_AP = 10;

    const DRAGON_NORMAL_DIFFICULTY = 'Normal';
    const DRAGON_NORMAL_LIFE = 8000;
    const DRAGON_NORMAL_POWER = 80;
    const FLAMETHROWER_NORMAL_ATTACK = 100;
    const DRAGON_NORMAL_AP = 15;

    const DRAGON_HARD_DIFFICULTY = 'Difficile';
    const DRAGON_HARD_LIFE = 15000;
    const DRAGON_HARD_POWER = 120;
    const FLAMETHROWER_HARD_ATTACK = 170;
    const DRAGON_HARD_AP = 20;

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
            ->setName('game:create-events')
            ->setDescription('Create events in game')
            ->setHelp('This command create events for the game.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shadowDragons = [
            [
                'name' => 'Le dragon de l\'ombre',
                'description' => 'Agée de plusieurs milliers d\'années, cette créature fait très certainement partie des premiers êtres vivants du nouveau monde. Ses 
        ailes ne lui permettent plus de voler, mais seulement à effrayer d\'autres monstres ou des populations entières. La légende raconte qu\'il cacherait 
        un merveilleux butin dans son antre. Mais attention, ce dragon à trois têtes est d\'une puissance hors du commun et fera de nombreuses victimes...',
                'imgMin' => 'dragon-ombre-min.jpg',
                'img' => 'dragon-ombre.jpg',
                'difficulty' => self::DRAGON_EASY_DIFFICULTY,
                'power' => self::DRAGON_EASY_POWER,
                'flamethrowerAttack' => self::FLAMETHROWER_EASY_ATTACK,
                'life' => self::DRAGON_EASY_LIFE,
                'ap' => self::DRAGON_EASY_AP
            ],
            [
                'name' => 'Le dragon de l\'ombre',
                'description' => 'Agée de plusieurs milliers d\'années, cette créature fait très certainement partie des premiers êtres vivants du nouveau monde. Ses 
        ailes ne lui permettent plus de voler, mais seulement à effrayer d\'autres monstres ou des populations entières. La légende raconte qu\'il cacherait 
        un merveilleux butin dans son antre. Mais attention, ce dragon à trois têtes est d\'une puissance hors du commun et fera de nombreuses victimes...',
                'imgMin' => 'dragon-ombre-min.jpg',
                'img' => 'dragon-ombre.jpg',
                'difficulty' => self::DRAGON_NORMAL_DIFFICULTY,
                'power' => self::DRAGON_NORMAL_POWER,
                'flamethrowerAttack' => self::FLAMETHROWER_NORMAL_ATTACK,
                'life' => self::DRAGON_NORMAL_LIFE,
                'ap' => self::DRAGON_NORMAL_AP
            ],
            [
                'name' => 'Le dragon de l\'ombre',
                'description' => 'Agée de plusieurs milliers d\'années, cette créature fait très certainement partie des premiers êtres vivants du nouveau monde. Ses 
        ailes ne lui permettent plus de voler, mais seulement à effrayer d\'autres monstres ou des populations entières. La légende raconte qu\'il cacherait 
        un merveilleux butin dans son antre. Mais attention, ce dragon à trois têtes est d\'une puissance hors du commun et fera de nombreuses victimes...',
                'imgMin' => 'dragon-ombre-min.jpg',
                'img' => 'dragon-ombre.jpg',
                'difficulty' => self::DRAGON_HARD_DIFFICULTY,
                'power' => self::DRAGON_HARD_POWER,
                'flamethrowerAttack' => self::FLAMETHROWER_HARD_ATTACK,
                'life' => self::DRAGON_HARD_LIFE,
                'ap' => self::DRAGON_HARD_AP
            ]
        ];

        foreach ($shadowDragons as $shadowDragon) {
            $event = new Event();
            $event->setName($shadowDragon['name']);
            $event->setDescription($shadowDragon['description']);
            $event->setImgMin($shadowDragon['imgMin']);
            $event->setImg($shadowDragon['img']);
            $event->setDifficulty($shadowDragon['difficulty']);
            $event->setPower($shadowDragon['power']);
            $event->setSpecialAttack($shadowDragon['flamethrowerAttack']);
            $event->setLife($shadowDragon['life']);
            $event->setAp($shadowDragon['ap']);

            $this->em->persist($event);
            $this->em->flush();
        }


        $output->writeln('L\'évenement des dragons à bien été ajouté !');
    }
}