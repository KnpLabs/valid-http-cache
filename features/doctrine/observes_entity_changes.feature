Feature: It observes entity changes
    In order to invalidate cache
    As a developer
    I want doctrine to observe entities changes and act accordingly

    Scenario: entity has not changed
        Given the entity "Scenario5\BlogPost":
        """
        <?php

        namespace Scenario5;

        use Knp\Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        /**
         * @Entity
         */
        class BlogPost implements Rule\LastModified
        {
            /**
             * @Id
             * @GeneratedValue
             * @Column(type="bigint")
             */
            public $id;

            /**
             * @Column
             */
            public $title;

            /**
             * @ORM\Column(type="datetime")
             */
            public $updatedAt;

            public function __construct()
            {
                $this->updatedAt = new \DateTime;
            }

            public function supports(Request $request)
            {
                return true;
            }

            public function getLastModified(Request $request)
            {
                return $this->updatedAt;
            }
        }

        """
        When  request asks for content that has not changed since "Sat, 29 Oct 1994 19:43:31 GMT"
        Then  response status should be 200

    Scenario: entity has changed
        Given the entity "Scenario6\BlogPost":
        """
        <?php

        namespace Scenario6;

        use Knp\Valid\Rule;
        use Symfony\Component\HttpFoundation\Request;

        /**
         * @Entity
         */
        class BlogPost implements Rule\LastModified
        {
            /**
             * @Id
             * @GeneratedValue
             * @Column(type="bigint")
             */
            public $id;

            /**
             * @Column
             */
            public $title;

            /**
             * @ORM\Column(type="datetime")
             */
            public $updatedAt;

            public function __construct()
            {
                $this->updatedAt = new \DateTime;
            }

            public function supports(Request $request)
            {
                return true;
            }

            public function getLastModified(Request $request)
            {
                return $this->updatedAt;
            }
        }

        """
        And   entity "Scenario5\BlogPost" changed "title" to "new value"
        When  request asks for content that has not changed since "Sat, 29 Oct 1994 19:43:31 GMT"
        Then  response status should be 200

