Feature: It uses ETag header
    In order to invalidate cache
    As a developer
    I want to use ETag header to check validaty of response

    Scenario: content has not changed
        Given I have configured the rule "Scenario1\Blogpost":
        """
        <?php

        namespace Scenario1;

        use Knp\Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        class BlogPost implements Rule\ETag
        {
            public function supports(Request $request)
            {
                return true;
            }

            public function getETag(Request $request)
            {
                return 'something';
            }
        }

        """
        When  request asks for content with ETag '"something"'
        Then  response status should be 304

    Scenario: content has changed
        Given I have configured the rule "Scenario2\Blogpost":
        """
        <?php

        namespace Scenario2;

        use Knp\Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        class BlogPost implements Rule\ETag
        {
            public function supports(Request $request)
            {
                return true;
            }

            public function getETag(Request $request)
            {
                return 'something';
            }
        }

        """
        When  request asks for content with ETag "something else"
        Then  response status should be 200
