<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Order;
use App\Services\OrderItemService;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends ApiController
{
    private OrderService $orderService;

    private OrderItemService $orderItemService;

    public function __construct(OrderService $orderService, OrderItemService $orderItemService)
    {
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
        $this->model = new Order;
        parent::__construct();
    }

    public function createOrder(OrderRequest $request)
    {
        $content = $request->all();
        $content['createdBy'] = $request->user()->id;

        $order = $this->orderService->createOrder($content);

        $orderItems = $content['orderItems'];
        foreach ($orderItems as $orderItem) {
            $orderItem['orderId'] = $order->id;
            $this->orderItemService->createOrderItem($orderItem);
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'order' => $order,
        ]);
    }

    public function getOrder(int $orderId)
    {
        $order = $this->orderService->getOrder($orderId);

        if (! isset($order)) {
            throw new ObjectNotFountException($orderId, 'Order');
        }

        return $this->successResponse($order);
    }

    public function getOrders(SearchRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $orders = $this->orderService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($orders instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($orders),
            'data' => $orders,
        ]);
    }

    public function deleteOrder(int $orderId)
    {
        $order = $this->orderService->getOrder($orderId);

        if (! isset($order)) {
            throw new ObjectNotFountException($orderId, 'Order');
        }

        if ($this->orderService->deleteOrder($order->id)) {
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'order' => $orderId,
                'message' => 'Order successfully deleted.',
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateOrder(OrderRequest $request, int $orderId)
    {
        $order = $this->orderService->getOrder($orderId);

        if (! isset($order)) {
            throw new ObjectNotFountException($orderId, 'Order');
        }

        $content = $request->all();

        $updatedOrder = $this->orderService->updateOrder($orderId, $content);
        $this->orderItemService->deleteAllOrderItems($updatedOrder);

        $orderItems = $content['orderItems'];
        foreach ($orderItems as $orderItem) {
            $orderItem['orderId'] = $updatedOrder;
            $this->orderItemService->createOrderItem($orderItem);
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'order' => $updatedOrder,
            'message' => 'Order successfully updated.',
        ]);
    }
}
