<?php

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Snippet\Context\SnippetsFriendlyInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Valid\ResponseManipulator;
use Knp\Valid\Rule\Rule;
use Knp\Valid\Doctrine\ListenerRule;

/**
 * Behat context class.
 */
class FeatureContext implements ContextInterface, SnippetsFriendlyInterface
{
    private $dir;
    private $fs;
    private $responseManipulator;
    private $listener;
    private $request;
    private $response;

    /**
     * Initializes context. Every scenario gets it's own context object.
     *
     * @param array $parameters Suite parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->dir = __DIR__.'/tmp';
        $this->fs = new Filesystem;
        $this->responseManipulator = new ResponseManipulator;
        $this->listener = new ListenerRule;
        $this->addRule($this->listener);
        $this->request = new Request;
        $this->response = new Response;
    }

    /**
     * @BeforeScenario
     **/
    public function removeDir()
    {
        $this->fs->remove($this->dir);
    }

    /**
     * @Given I have configured the rule :rule:
     */
    public function iHaveConfiguredARule($rule, PyStringNode $string)
    {
        $this->fs->mkdir($this->dir.'/rule');
        $file = $this->dir.'/rule/'.$rule.'.php';
        file_put_contents($file, $string);
        require $file;
        $this->addRule(new $rule);
    }

    public function addRule(Rule $rule)
    {
        $this->responseManipulator->addRule($rule);
    }

    /**
     * @Given request asks for content that has not changed since :since
     */
    public function requestAsksForContentThatHasNotChangedSince($since)
    {
        $this->request->headers->set('If-Modified-Since', $since);
        $this->responseManipulator->handle($this->request, $this->response);
    }

    /**
     * @Given request asks for content with ETag :etag
     */
    public function requestAsksForContentWithETag($etag)
    {
        $this->request->headers->set('If-None-Match', $etag);
        $this->responseManipulator->handle($this->request, $this->response);
    }

    /**
     * @Then response status should be :code
     */
    public function responseStatusShouldBe($code)
    {
        if ($this->response->getStatusCode() != $code) {
            throw new \LogicException(sprintf(
                'Status code %s should be %s',
                $this->response->getStatusCode(),
                $code
            ));
        }
    }

    /**
     * @Given the entity :name:
     **/
    public function theEntity($name, PyStringNode $entity)
    {
        $this->fs->mkdir($this->dir.'/entities');
        $file = $this->dir.'/entities/'.$name.'.php';
        file_put_contents($file, $entity);
        require $file;
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(
            $this->dir.'/entities',
        ));
        $dbParams = array('driver' => 'pdo_sqlite', 'memory' => true);
        $this->om = \Doctrine\ORM\EntityManager::create($dbParams, $config);
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->om);
        $metadata = $this->om->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($metadata);
        $this->om->getEventManager()->addEventSubscriber($this->listener);
    }

    /**
     * @Given entity :name changed :property to :value
     **/
    public function entityChanged($name, $property, $value)
    {
        $entity = new $name;
        $entity->$property = $value;

        $this->om->persist($entity);
        $this->om->flush();
    }
}
