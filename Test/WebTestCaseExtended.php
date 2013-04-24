<?php

namespace Sweet\GalleryBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

require_once(__DIR__ . "/../../../../app/AppKernel.php");

/**
 * Base class for tests, resets database
 */
class WebTestCaseExtended extends WebTestCase
{
    protected $application;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Set up tests
     */
    public function setUp()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->application->setAutoExit(false);

        $this->em = $this->getContainer()
                ->get('doctrine')
                ->getManager();

        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:create");
        $this->runConsole("doctrine:fixtures:load", array("--fixtures" => __DIR__ . "/../DataFixtures"));
    }

    /**
     * Run given symfony command with options in test env
     *
     * @param string $command
     * @param Array  $options
     */
    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        $this->application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->application->getKernel()->getContainer();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

    /**
     * @param Symfony\Bundle\FrameworkBundle\Client $client
     * @param string                                $url
     * @param array                                 $data
     *
     * @return Symfony\Bundle\FrameworkBundle\Client 
     */
    protected function requestPost($client, $url, $data)
    {
        $client->request(
            'POST',
            $url,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );

        return $client;
    }

}