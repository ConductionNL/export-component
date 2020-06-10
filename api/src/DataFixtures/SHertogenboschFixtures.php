<?php

namespace App\DataFixtures;

use App\Entity\Export;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SHertogenboschFixtures extends Fixture
{
    private $params;
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->commonGroundService = $commonGroundService;
        $this->params = $params;
    }

    public function load(ObjectManager $manager)
    {

        // Lets make sure we only run these fixtures on larping enviroment
        if (
            $this->params->get('app_domain') != 'shertogenbosch.commonground.nu' &&
            strpos($this->params->get('app_domain'), 'shertogenbosch.commonground.nu') == false &&
            $this->params->get('app_domain') != 's-hertogenbosch.commonground.nu' &&
            strpos($this->params->get('app_domain'), 's-hertogenbosch.commonground.nu') == false
        ) {
            //return false;
        }

        $id = Uuid::fromString('c1aaf4e2-5581-4260-a933-4aa790ab6170');
        $export = new Export();
        $export->setName('Alle verzoeken export');
        $export->setDescription('Deze export exporteerd alle verzoeken');
        $export->setContent(file_get_contents(dirname(__FILE__).'/SHertogenbosch/verzoeken-alle.csv.twig', 'r'));
        $export->setContentType('text/csv');
        $manager->persist($export);
        $export->setId($id);
        $manager->persist($export);

        $id = Uuid::fromString('9e34188d-83e6-463c-89f2-2b4b9a30cebe');
        $export = new Export();
        $export->setName('Open verzoeken export');
        $export->setDescription('Deze export exporteerd alle OPENSTAANDE verzoeken');
        $export->setContent(file_get_contents(dirname(__FILE__).'/SHertogenbosch/verzoeken-openstaand.csv.twig', 'r'));
        $export->setContentType('text/csv');
        $manager->persist($export);
        $export->setId($id);
        $manager->persist($export);

        $manager->flush();
    }
}
