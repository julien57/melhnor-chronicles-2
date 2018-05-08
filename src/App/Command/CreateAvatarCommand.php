<?php

namespace App\Command;

use App\Entity\Avatar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAvatarCommand extends Command
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
            ->setName('game:create-avatars')
            ->setDescription('Creates Avatars')
            ->setHelp('This command create a Avatars for player')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $worshipers = 5;
        for ($i = 1; $i <= $worshipers; $i++) {
            $names = [
                [
                    'name' => 'adorateurs'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);

                $this->em->persist($avatar);
            }
        }

        $redeemers = 16;
        for ($i = 6; $i <= $redeemers; $i++) {
            $names = [
                [
                    'name' => 'redempteurs'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);

                $this->em->persist($avatar);
            }
        }

        $dwarfs = 34;
        for ($i = 17; $i <= $dwarfs; $i++) {
            $names = [
                [
                    'name' => 'nains'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $naoitte = 47;
        for ($i = 35; $i <= $naoitte; $i++) {
            $names = [
                [
                    'name' => 'nao-oitte'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $sellers = 56;
        for ($i = 48; $i <= $sellers; $i++) {
            $names = [
                [
                    'name' => 'marchands'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $hordes = 65;
        for ($i = 57; $i <= $hordes; $i++) {
            $names = [
                [
                    'name' => 'horde'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $pirates = 80;
        for ($i = 66; $i <= $pirates; $i++) {
            $names = [
                [
                    'name' => 'pirates'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $light = 100;
        for ($i = 81; $i <= $light; $i++) {
            $names = [
                [
                    'name' => 'lumiere'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $elfes = 120;
        for ($i = 101; $i <= $elfes; $i++) {
            $names = [
                [
                    'name' => 'elfe'.$i,
                ],
            ];

            foreach ($names as $name) {
                $avatar = new Avatar();
                $avatar->setIdAvatar($name['name']);
                $this->em->persist($avatar);
            }
        }

        $this->em->flush();

        $output->writeln('Les avatars ont bien été enregistrés !');
    }
}
