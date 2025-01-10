<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidInputTypeException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Models\Tenant\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->model = new Product;
        parent::__construct();
    }

    public function createProduct(Request $request)
    {
        $content = $request->all();

        $validator = Validator::make($content, [
            'label' => 'required|string',
            'barcode' => 'string',
            /*'cipcode' => 'string',
            'ndc' => 'string',
            'cardNumber' => 'numeric',*/
            'acqCost' => 'numeric',
            'wholesaleCost' => 'numeric',
            'retailCost' => 'numeric',
            'unitCost' => 'numeric',
            'description' => 'nullable|string',
            'qoh' => 'numeric',
            'minQuantity' => 'numeric',
            'maxQuantity' => 'numeric',
        ]);

        if ($validator->fails()) {
            throw new InvalidInputTypeException($validator);
        }

        $product = $this->productService->createProduct(
            $content['label'],
            $content['pkgSize'],
            $content['barcode'],
            /*$content['cipCode'],
            $content['ndc'],
            $content['cardNumber'],*/
            $content['markup'],
            $content['acqCost'],
            $content['wholesaleCost'],
            $content['retailCost'],
            $content['unitCost'],
            $content['description'],
            $content['qoh'],
            $content['minQuantity'],
            $content['maxQuantity'],
            $content['shelfId'],
            $content['productCategoryId'],
            $content['taxId'],
            $content['isPrescription'],
            //$content['isGeneric'],
            $content['isActive']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'product' => $product,
        ]);
    }

    public function getProduct(int $productId)
    {
        $product = $this->productService->getProduct($productId);

        if (! isset($product)) {
            throw new ObjectNotFountException($productId, 'Product');
        }

        return $this->successResponse($product);
    }

    public function getProducts()
    {
        $products = $this->productService->getProducts();

        if (! assert($products instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $this->successResponse($products);
    }

    public function deleteProduct(int $productId)
    {
        $product = $this->productService->getProduct($productId);

        if (! isset($product)) {
            throw new ObjectNotFountException($productId, 'Product');
        }

        if ($this->productService->deleteProduct($product->id)) {
            return new JsonResponse([
                'code' => Response::HTTP_OK,
                'product' => $productId,
                'message' => 'Product successfully deleted.',
            ]);
        } else {
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProduct(Request $request, int $productId)
    {
        $product = $this->productService->getProduct($productId);

        if (! isset($product)) {
            throw new ObjectNotFountException($productId, 'Product');
        }

        $content = $request->all();

        $validator = Validator::make($content, [
            'label' => 'required|string',
            'barcode' => 'string',
            /*'cipcode' => 'string',
            'ndc' => 'string',
            'cardNumber' => 'numeric',*/
            'acqCost' => 'numeric',
            'wholesaleCost' => 'numeric',
            'retailCost' => 'numeric',
            'unitCost' => 'numeric',
            'description' => 'nullable|string',
            'qoh' => 'numeric',
            'minQuantity' => 'numeric',
            'maxQuantity' => 'numeric',
        ]);

        if ($validator->fails()) {
            throw new InvalidInputTypeException($validator);
        }

        $updatedProduct = $this->productService->updateProduct(
            $product,
            $content['label'],
            $content['pkgSize'],
            $content['barcode'],
            /*$content['cipCode'],
            $content['ndc'],
            $content['cardNumber'],*/
            $content['markup'],
            $content['acqCost'],
            $content['wholesaleCost'],
            $content['retailCost'],
            $content['unitCost'],
            $content['description'],
            $content['qoh'],
            $content['minQuantity'],
            $content['maxQuantity'],
            $content['shelfId'],
            $content['productCategoryId'],
            $content['taxId'],
            $content['isPrescription'],
            //$content['isGeneric'],
            $content['isActive']
        );

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'product' => $updatedProduct,
        ]);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $limit = $request->query->get('limit');

        $products = $this->productService->search(
            $search,
            $sortBy,
            $sortOrder,
            $limit
        );

        if (! assert($products instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $this->successResponse($products);

    }
}
