<?php

namespace App\Command;

use App\Entity\User;
use App\Services\RhService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    protected static $defaultName = "restau:people:import";
    private $response;

    private $rhService;

    public function __construct(string $name = null, RhService $rhService)
    {
        $this->rhService = $rhService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('restau:people:import')
            ->setDescription('Creates a new users')
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->rhService->getPeople();

        foreach ($result as $person) {
            $entityManager = $this->getContainer()->get('doctrine')->getManager();
            if ($entityManager instanceof EntityManager) {
                $existUser = $entityManager->getRepository(User::class)->findOneBy(
                    array("email" => $person['email'])
                );

                if (!$existUser) {
                    $user = new User();
                    $user->setUsername($person['id']);
                    $user->setFirstname($person['firstname']);
                    $user->setLastname($person['lastname']);
                    $user->setEmail($person['email']);
                    $user->setJobtitle($person['jobtitle']);
                    $user->setEnabled(1);
                    $user->setCreatedAt(new \DateTime());
                    $user->setUpdatedAt(new \DateTime());
                    try{
                        $entityManager->persist($user);
                        $entityManager->flush();
                        dump('Utilisateur ' . $user->getUsername() . "ajouté à la base de donnée");
                    }catch (\Exception $e){
                        return $e->getMessage();
                    }
                }
            }
        }
    }
}