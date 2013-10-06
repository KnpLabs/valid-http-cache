Feature: It uses Last-Modified header
    In order to invalidate cache
    As a developer
    I want to use Last-Modified header to check validaty of response

    Scenario: content has not changed
        Given I have configured the rule "Scenario3\Blogpost":
        """
        <?php

        namespace Scenario3;

        use Knp\Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        class BlogPost implements Rule\LastModified
        {
            public function supports(Request $request)
            {
                return true;
            }

            public function getLastModified(Request $request)
            {
                return \DateTime::createFromFormat('D, d M Y H:i:s', 'Sat, 29 Oct 1994 19:43:31', new \DateTimeZone('UTC'));
            }
        }

        """
        When  request asks for content that has not changed since "Sat, 29 Oct 1994 19:43:31 GMT"
        Then  response status should be 304

    Scenario: content has changed
        Given I have configured the rule "Scenario4\Blogpost":
        """
        <?php

        namespace Scenario4;

        use Knp\Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        class BlogPost implements Rule\LastModified
        {
            public function supports(Request $request)
            {
                return true;
            }

            public function getLastModified(Request $request)
            {
                return \DateTime::createFromFormat('D, d M Y H:i:s', 'Sat, 29 Oct 1997 19:43:31', new \DateTimeZone('UTC'));
            }
        }

        """
        When  request asks for content that has not changed since "Sat, 29 Oct 1994 19:43:31 GMT"
        Then  response status should be 200
