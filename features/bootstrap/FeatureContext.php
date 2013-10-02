<?php

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Snippet\Context\SnippetsFriendlyInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Behat context class.
 */
class FeatureContext implements ContextInterface, SnippetsFriendlyInterface
{
    private $dir;
    /**
     * Initializes context. Every scenario gets it's own context object.
     *
     * @param array $parameters Suite parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->dir = __DIR__;
        $this->fs = new Filesystem;
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

        $file = $this->dir.'/rule/'.$rule;

        file_put_contents($file, $string);

        require $file;
    }

    /**
     * @Given the content has not changed
     */
    public function theContentHasNotChanged()
    {
        throw new PendingException();
    }

    /**
     * @When request arrives
     */
    public function requestArrives()
    {
        throw new PendingException();
    }

    /**
     * @Then response status should be :arg1
     */
    public function responseStatusShouldBe($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given response should have the same header ETag
     */
    public function responseShouldHaveTheSameHeaderEtag()
    {
        throw new PendingException();
    }

    /**
     * @Given the content has changed
     */
    public function theContentHasChanged()
    {
        throw new PendingException();
    }

    /**
     * @Given response should have a different header ETag
     */
    public function responseShouldHaveADifferentHeaderEtag()
    {
        throw new PendingException();
    }
}
