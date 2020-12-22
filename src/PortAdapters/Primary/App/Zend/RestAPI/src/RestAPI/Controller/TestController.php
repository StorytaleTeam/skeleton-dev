<?php

namespace RestAPI\Controller;

use Storytale\SkeletonDev\Application\Command\TestOrder\TestOrderService;
use Storytale\SkeletonDev\Application\Query\TestOrder\SearchOrderService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class TestController extends AbstractActionController
{
    /** @var TestOrderService */
    private TestOrderService $testOrderService;

    /** @var SearchOrderService */
    private SearchOrderService $searchOrderService;

    public function __construct(TestOrderService $orderService, SearchOrderService $searchOrderService)
    {
        $this->testOrderService = $orderService;
        $this->searchOrderService = $searchOrderService;
    }

    public function indexAction()
    {
        return new JsonModel(['success' => true, 'message' => 'TestController for test demonstration']);
    }


    public function createOrderAction()
    {
        $response = $this->testOrderService->createOrder();

        return new JsonModel($response->jsonSerialize());
    }

    public function updateOrderAction()
    {
        $response = $this->testOrderService->orderWasSold(3);

        return new JsonModel($response->jsonSerialize());
    }

    public function findByIdAction()
    {
        $response = $this->searchOrderService->find(1);

        return new JsonModel([
            'success' => true,
            'result' => $response,
        ]);
    }
}