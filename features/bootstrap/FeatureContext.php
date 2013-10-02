<?php

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Snippet\Context\SnippetsFriendlyInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;
use Valid\ResponseManipulator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Behat context class.
 */
class FeatureContext implements ContextInterface, SnippetsFriendlyInterface
{
    private $dir;
    private $fs;
    private $responseManipulator;
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
        require_once $file;
        $this->responseManipulator->addRule(new $rule);
    }

    /**
     * @Given the content has not changed since :since
     */
    public function theContentHasNotChangedSince($since)
    {
        $this->request->headers->set('If-Modified-Since', $since);
    }

    /**
     * @When request arrives
     */
    public function requestArrives()
    {
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
     * @Given response should have the same header ETag
     */
    public function responseShouldHaveTheSameHeaderEtag()
    {
    }

    /**
     * @Given the content has changed
     */
    public function theContentHasChanged()
    {
    }

    /**
     * @Given response should have a different header ETag
     */
    public function responseShouldHaveADifferentHeaderEtag()
    {
    }
}
