<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Models\Tenant\Customer;
use App\Services\CustomerService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CustomerController extends ApiController
{
    private CustomerService $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->model = new Customer();
        parent::__construct();
    }

    public function createCustomer(Request $request) 
    {
        $content = $request->all();

        $validator = Validator::make($content, [
            'name' => 'required|string',
            'email' => 'email',
            'creditLimit' => 'numeric|min:0',
            'balance' => 'numeric|min:0',
            'deposit' => 'numeric|min:0'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $customer = $this->customerService->createCustomer(
            $content['name'],
            $content['email'],
            $content['creditLimit'],
            $content['balance'],
            $content['deposit'],
            $content['phone'],
            $content['address'],
            $content['city']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'customer' => $customer
        ]);
    }

    public function getCustomer(int $customerId)
    {
        $customer = $this->customerService->getCustomer($customerId);

        if(! isset($customer)){
            throw new ObjectNotFountException($customerId, 'Customer');
        }
        return $this->successResponse($customer);
    }

    public function getCustomers()
    {
        $customers = $this->customerService->getCustomers();

        if (! assert($customers instanceof Collection)) {
            throw new InternalServerErrorException;
        }
        return $this->successResponse($customers);
    }

    public function deleteCustomer(int $customerId)
    {
        $customer = $this->customerService->getCustomer($customerId);

        if(! isset($customer)){
            throw new ObjectNotFountException($customerId, 'Customer');
        }

        if($this->customerService->deleteCustomer($customer->id)){
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'customer' => $customerId,
                'message' => 'Customer successfully deleted.'
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCustomer(Request $request, int $customerId)
    {
        $customer = $this->customerService->getCustomer($customerId);

        if(! isset($customer)){
            throw new ObjectNotFountException($customerId, 'Customer');
        }

        $content = $request->all();

        $validator = Validator::make($content, [
            'name' => 'required|string',
            'email' => 'email',
            'creditLimit' => 'numeric|min:0',
            'balance' => 'numeric|min:0',
            'deposit' => 'numeric|min:0'
        ]);

        if($validator->fails()){
            throw new InvalidInputTypeException($validator);
        }

        $updatedCustomer = $this->customerService->updateCustomer(
            $customer,
            $content['name'],
            $content['email'],
            $content['creditLimit'],
            $content['balance'],
            $content['deposit'],
            $content['phone'],
            $content['address'],
            $content['city']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'customer' => $updatedCustomer
        ]);
    }
}
