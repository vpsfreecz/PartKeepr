<?php

namespace PartKeepr\AuthBundle\Action;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use PartKeepr\AuthBundle\Entity\User;
use PartKeepr\AuthBundle\Exceptions\UserLimitReachedException;
use PartKeepr\AuthBundle\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostUserAction
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserService
     */
    private $userService;

     private $serializerContextBuilder;

    public function __construct(
        SerializerInterface $serializer,
        UserService $userService,
        SerializerContextBuilderInterface $serializerContextBuilder
    ) {
        $this->serializer = $serializer;
        $this->userService = $userService;
         $this->serializerContextBuilder = $serializerContextBuilder;
    }

    /**
     * Retrieves a collection of resources.
     *
     * @Route(
     *     name="user_post",
     *     path="/api/users",
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="post"}
     * )
     * @Security("has_role('ROLE_USER')")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $attributes = RequestAttributesExtractor::extractAttributes($request);

        if ($this->userService->checkUserLimit() === true) {
            throw new UserLimitReachedException();
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);

        /**
         * @var User
         */
        $data = $this->serializer->deserialize(
            $request->getContent(),
            $attributes["resource_class"],
            "jsonld",
            $context
        );

        $data->setProvider($this->userService->getBuiltinProvider());
        $data->setLegacy(false);
        $this->userService->syncData($data);

        $data->setNewPassword('');
        $data->setPassword('');

        return $data;
    }
}
