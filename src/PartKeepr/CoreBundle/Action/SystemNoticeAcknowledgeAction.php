<?php

namespace PartKeepr\CoreBundle\Action;

use PartKeepr\CoreBundle\Services\SystemNoticeService;
use PartKeepr\CoreBundle\Entity\SystemNotice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SystemNoticeAcknowledgeAction
{
    /**
     * @var SystemNoticeService
     */
    private $systemNoticeService;


    public function __construct(
        SystemNoticeService $systemNoticeService
    ) {
        $this->systemNoticeService = $systemNoticeService;
    }

    /**
     * Retrieves a collection of resources.
     *
     * @Route(
     *     name="system_notice_acknowledge",
     *     path="/api/system_notices/{id}/acknowledge",
     *     defaults={"_api_resource_class"=SystemNotice::class, "_api_item_operation_name"="put"}
     * )
     * @Security("has_role('ROLE_USER')")
     * @Method("PUT")
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke($data = null)
    {
        $this->systemNoticeService->acknowledge($data);
        return $data;
    }
}
