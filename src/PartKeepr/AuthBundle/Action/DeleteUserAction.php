<?php

namespace PartKeepr\AuthBundle\Action;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use PartKeepr\AuthBundle\Entity\User;
use PartKeepr\AuthBundle\Exceptions\UserProtectedException;
use PartKeepr\AuthBundle\Services\UserPreferenceService;
use PartKeepr\AuthBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Custom API action deleting an user.
 */
class DeleteUserAction
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var UserPreferenceService
     */
    private $userPreferenceService;

    public function __construct(
        UserService $userService,
        UserPreferenceService $userPreferenceService
    ) {
        $this->userService = $userService;
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * Retrieves a collection of resources.
     *
     * @Route(
     *     name="user_delete",
     *     path="/api/users/{id}",
     *     defaults={"_api_resource_class"=User::class, "_api_item_operation_name"="delete"}
     * )
     * @Security("has_role('ROLE_USER')")
     * @Method("DELETE")
     * @param Request $request
     * @return User
     * @throws UserProtectedException
     */
    public function __invoke($data)
    {
        /**
         * @var $data User
         */
        if ($data->isProtected()) {
            throw new UserProtectedException();
        }

        $this->userService->deleteFOSUser($data);
        $this->userPreferenceService->deletePreferences($data);

        return $data;
    }
}
