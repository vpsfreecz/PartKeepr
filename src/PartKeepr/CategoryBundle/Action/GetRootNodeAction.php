<?php

namespace PartKeepr\CategoryBundle\Action;

use PartKeepr\CategoryBundle\Exception\RootNodeNotFoundException;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns the tree root node.
 */
class GetRootNodeAction
{
    use ActionUtilTrait;

    /**
     * @var ManagerRegistry
     */
    private $manager;

    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Retrieves a collection of resources.
     *
     * @param Request $request
     *
     * @throws RuntimeException|RootNodeNotFoundException
     *
     * @return array|\Dunglas\ApiBundle\Model\PaginatorInterface|\Traversable
     */
    public function __invoke(Request $request)
    {
        list($resourceType) = $this->extractAttributes($request);

        /*
         * @var ResourceInterface $resourceType
         */
        $repository = $this->manager->getRepository($resourceType->getEntityClass());

        /*
         * @var $repository AbstractTreeRepository
         */
        $rootNodes = $repository->getRootNodes();

        if (count($rootNodes) == 0) {
            throw new RootNodeNotFoundException();
        }

        $rootNode = reset($rootNodes);

        return $rootNode;
    }
}
