<?php

namespace Sweet\GalleryBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

require_once(__DIR__ . "/../../../../app/AppKernel.php");

/**
 * Base class for tests, resets database
 */
class WebTestCaseExtended extends WebTestCase
{
    protected $application;
    protected $uploadsDirExists = false;
    protected $uploadsDirChmod = false;

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

        $this->createUploadDir();
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
        $this->removeUploadDir();
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

    /**
     * Get file fixtures dir
     * @return string
     */
    protected function getFileFixturePath()
    {
        $d = DIRECTORY_SEPARATOR;

        return realpath(__DIR__ ."{$d}..{$d}Tests{$d}FileFixture");
    }

    /**
     * Get file fixture as UploadedFile
     * @param string $filename
     * 
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected function getFileFixture($filename)
    {
        $filepath = $this->getFileFixturePath() . DIRECTORY_SEPARATOR . $filename;

        return new UploadedFile(
            $filepath,
            $filename,
            MimeTypeGuesser::getInstance()->guess($filepath),
            filesize($filepath)
        );
    }

    protected function getUploadDir()
    {
        return 'uploads';
    }

    protected function createUploadDir()
    {
        if (!is_dir($this->getUploadDir())) {
            mkdir($this->getUploadDir(), 0777);
        } else {
            $this->uploadsDirExists = true;
            $this->uploadsDirChmod = substr(sprintf('%o', fileperms($this->getUploadDir())), -4);
            chmod($this->getUploadDir(), 0777);
        }
    }

    protected function removeUploadDir()
    {
        if ($this->uploadsDirExists) {
            chmod($this->getUploadDir(), $this->uploadsDirChmod);
        } else {
            rmdir($this->getUploadDir());
        }
    }
}