<?php

namespace Sweet\GalleryBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

require_once(__DIR__ . "/../../../../app/AppKernel.php");

/**
 * Base class for tests, resets database
 */
class BaseController extends WebTestCase
{
    protected $_application;

    /**
     * Set up tests
     */
    public function setUp()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->_application->setAutoExit(false);
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
        $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->_application->getKernel()->getContainer();
    }
}