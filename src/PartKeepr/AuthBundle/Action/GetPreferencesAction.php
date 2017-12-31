<?php

namespace PartKeepr\AuthBundle\Action;

use PartKeepr\AuthBundle\Services\UserPreferenceService;
use PartKeepr\AuthBundle\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * Returns the tree root node.
 */
class GetPreferencesAction
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var UserPreferenceService
     */
    private $userPreferenceService;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        UserService $userService,
        UserPreferenceService $userPreferenceService,
        Serializer $serializer
    ) {
        $this->userService = $userService;
        $this->userPreferenceService = $userPreferenceService;
        $this->serializer = $serializer;
    }

    /**
     * Retrieves a collection of resources.
     *
     * @Route(
     *     name="user_preferences_get",
     *     path="/api/user_preferences",
     * )
     * @Security("has_role('ROLE_USER')")
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke($data = null, Request $request)
    {
        $user = $this->userService->getUser();

        $preferences = $this->userPreferenceService->getPreferences($user);

        $serializedData = $this->serializer->normalize(
            $preferences,
            'json'
        );

        return new JsonResponse($serializedData);
    }
}
