<?php

namespace App\Command;

use App\Entity\Avatar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command adds the avatar names for the Avatar entity
 * The images are predefined in the game (120 avatars for the moment)
 * if i want to add avatars, i create a new loop 'for' with a constant start at 121 :
 *      NAME_NUMBER_START = 121;
 * For the end, depend how many images
 */
class CreateAvatarCommand extends Command
{
    const WHORSHIPERS_NUMBER_START = 1;
    const WHORSHIPERS_NUMBER_END = 5;

    const REDEEMERS_NUMBER_START = 6;
    const REDEEMERS_NUMBER_END = 16;

    const DWARFS_NUMBER_START = 17;
    const DWARFS_NUMBER_END = 34;

    const NAOITTE_NUMBER_START = 35;
    const NAOITTE_NUMBER_END = 47;

    const SELLERS_NUMBER_START = 48;
    const SELLERS_NUMBER_END = 56;

    const HORDES_NUMBER_START = 57;
    const HORDES_NUMBER_END = 65;

    const PIRATES_NUMBER_START = 66;
    const PIRATES_NUMBER_END = 80;

    const LIGHT_NUMBER_START = 81;
    const LIGHT_NUMBER_END = 100;

    const ELFES_NUMBER_START = 101;
    const ELFES_NUMBER_END = 120;

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
            ->setName('game:create-avatars')
            ->setDescription('Creates Avatars')
            ->setHelp('This command create a Avatars for player')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = self::WHORSHIPERS_NUMBER_START; $i <= self::WHORSHIPERS_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('adorateurs'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::REDEEMERS_NUMBER_START; $i <= self::REDEEMERS_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('redempteurs'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::DWARFS_NUMBER_START; $i <= self::DWARFS_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('nains'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::NAOITTE_NUMBER_START; $i <= self::NAOITTE_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('nao-oitte'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::SELLERS_NUMBER_START; $i <= self::SELLERS_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('marchands'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::HORDES_NUMBER_START; $i <= self::HORDES_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('horde'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::PIRATES_NUMBER_START; $i <= self::PIRATES_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('pirates'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::LIGHT_NUMBER_START; $i <= self::LIGHT_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('lumiere'.$i);
            $this->em->persist($avatar);
        }

        for ($i = self::ELFES_NUMBER_START; $i <= self::ELFES_NUMBER_END; $i++) {
            $avatar = new Avatar();
            $avatar->setIdAvatar('elfe'.$i);
            $this->em->persist($avatar);
        }

        $this->em->flush();

        $output->writeln('Les avatars ont bien été enregistrés !');
    }
}
