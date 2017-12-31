<?php

namespace PartKeepr\AuthBundle\Action;

use PartKeepr\AuthBundle\Entity\User;
use PartKeepr\AuthBundle\Exceptions\UserLimitReachedException;
use PartKeepr\AuthBundle\Exceptions\UserProtectedException;
use PartKeepr\AuthBundle\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PutUserAction
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        SerializerInterface $serializer,
        UserService $userService
    ) {
        $this->serializer = $serializer;
        $this->userService = $userService;
    }

    /**
     * Retrieves a collection of resources.
     *
     * @Route(
     *     name="user_put",
     *     path="/api/users/{id}",
     *     defaults={"_api_resource_class"=User::class, "_api_item_operation_name"="put"}
     * )
     * @Security("has_role('ROLE_USER')")
     * @Method("PUT")
     * @param Request $request
     * @return User
     * @throws UserProtectedException
     * @throws UserLimitReachedException
     */
    public function __invoke(Request $request, $data)
    {
        /**
         * @var $data User
         */
        if ($data->isProtected()) {
            throw new UserProtectedException();
        }

        if ($data->isActive()) {
            if ($this->userService->checkUserLimit()) {
                throw new UserLimitReachedException();
            }
        }

        $this->userService->syncData($data);
        $data->setNewPassword('');
        $data->setPassword('');
        $data->setLegacy(false);

        return $data;
    }
}
