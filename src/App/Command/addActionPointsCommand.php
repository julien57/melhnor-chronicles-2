<?php

namespace App\Command;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to generate 5 action points to all players in the game
 * Works with a CRON task for each hour
 * Max : 50 action points
 */
class addActionPointsCommand extends Command
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
            ->setName('game:add-action-points')
            ->setDescription('add action points for all players')
            ->setHelp('This command increase 5 action points every hour for all players.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $players = $this->em->getRepository(Player::class)->findAll();

        foreach ($players as $player) {
            $pointsOfPlayer = $player->getActionPoints();
            $totalPoints = $pointsOfPlayer += 5;

            if ($totalPoints >= 50) {
                $player->setActionPoints(50);
            } else {
                $player->setActionPoints($totalPoints);
            }
        }

        $this->em->flush();

        $output->writeln('Les joueurs on été crédités de 5 points d\'action !');
    }
}
