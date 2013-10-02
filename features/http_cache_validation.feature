Feature: It helps in/validation of cacheable HTTP responses
    In order to not repeat myself
    As a developer
    I want to have an entry point to describe common in/validation rules

    Background:
        Given I have configured the rule "Blogpost":
        """
        <?php

        use Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        class BlogPost implements Rule\ETag, Rule\LastModified
        {
            public function supports(Request $request)
            {
                return true;//$request->attributes->get('_route') === 'app_blogpost_show';
            }

            public function getETag(Request $request)
            {
                // called after resource revolver: we have access to resolved controller args
                return 'aa';

                return md5($request->attributes->get('blogPost')->getUpdatedAt()->format('U'));
            }

            public function getLastModified(Request $request)
            {
                // called after resource revolver: we have access to resolved controller args
                return \DateTime::createFromFormat('D, d M Y H:i:s', 'Sat, 29 Oct 1994 19:43:31', new \DateTimeZone('UTC'));

                return $request->attributes->get('blogPost')->getUpdatedAt();
            }
        }

        """

    Scenario: content has not changed
        Given the content has not changed since "Sat, 29 Oct 1994 19:43:31 GMT"
        When  request arrives
        Then  response status should be 304
        And   response should have the same header ETag

    Scenario: content has changed
        Given the content has changed
        When  request arrives
        Then  response status should be 200
        And   response should have a different header ETag
