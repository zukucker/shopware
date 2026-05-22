<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Api\Controller;

use Shopware\Core\Framework\Log\LogCleanupService;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
class LogController extends AbstractController
{
    public function __construct(
        private readonly LogCleanupService $logCleanupService
    ) {
    }

    #[Route(
        path: '/api/_action/system/log/clear',
        name: 'api.action.system.log.clear',
        methods: ['POST']
    )]
    public function clear(): JsonResponse
    {
        $count = $this->logCleanupService->clear();

        return new JsonResponse([
            'deleted' => $count,
        ]);
    }
}
