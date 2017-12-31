<?php

namespace PartKeepr\AuthBundle\Action;

use PartKeepr\AuthBundle\Services\UserPreferenceService;
use PartKeepr\AuthBundle\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Returns the tree root node.
 */
class SetPreferenceAction
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
     *     name="user_preferences_post",
     *     path="/api/user_preferences",
     * )
     * @Security("has_role('ROLE_USER')")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $user = $this->userService->getUser();

        $data = json_decode($request->getContent());

        if (property_exists($data, 'preferenceKey') && property_exists($data, 'preferenceValue')) {
            $preference = $this->userPreferenceService->setPreference($user, $data->preferenceKey,
                $data->preferenceValue);
        } else {
            throw new \Exception('Invalid format');
        }

        /*
         * @var ResourceInterface $resourceType
         */

        $serializedData = $this->serializer->normalize(
            $preference,
            'json'
        );

        return new JsonResponse($serializedData);
    }
}
