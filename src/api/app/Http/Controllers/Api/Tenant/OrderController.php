<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CompleteOrderRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Order;
use App\Services\OrderItemService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class OrderController extends ApiController
{
    private OrderService $orderService;

    private OrderItemService $orderItemService;

    public function __construct(OrderService $orderService, OrderItemService $orderItemService)
    {
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
        $this->model = new Order;
        parent::__construct($orderService);
    }

    public function getOrders(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $fromDate = $request->query->get('startDate');
            $toDate = $request->query->get('endDate');

            $orders = $this->orderService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy,
                $fromDate,
                $toDate
            );

            return $this->successResponse(
                $orders,
                'Orders successfully retrieved!',
                Response::HTTP_OK,
                $this->orderService->getModelCount()
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createOrder(OrderRequest $request): JsonResponse
    {
        try {
            $content = $request->safe()->toArray();
            $orderContent = [];
            $orderContent['invoiceCode'] = $content['invoiceCode'];
            $orderContent['supplierId'] = $content['supplierId'];
            $orderContent['invoiceDate'] = $content['invoiceDate'];
            $orderContent['status'] = $content['status'];
            $orderContent['subTotal'] = $content['subTotal'];
            $orderContent['taxTotal'] = $content['taxTotal'];
            $orderContent['total'] = $content['total'];
            $orderContent['charges'] = $content['charges'];
            $orderContent['comment'] = $content['comment'];
            $orderItems = $content['orderItems'];

            $order = $this->orderService->create($orderContent);
            if ($order->id) {
                $orderItems = array_map(function ($orderItem) use ($order) {
                    $orderItem['orderId'] = $order->id;

                    return $orderItem;
                }, $orderItems);

                $this->orderItemService->bulkCreate($orderItems);
            }

            return $this->successResponse($order, class_basename($this->model).' successfully created');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create '.class_basename($this->model));
        }
    }

    public function complete(CompleteOrderRequest $request): JsonResponse
    {
        $content = $request->safe()->toArray();
        $order = $this->orderService->completeOrder($content['orderId']);

        return $this->successResponse($order, 'Order completed!');
    }
}
