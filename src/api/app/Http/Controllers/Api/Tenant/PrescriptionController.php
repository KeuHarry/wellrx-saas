<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PrescriptionRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Prescription;
use App\Services\PrescriptionService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PrescriptionController extends ApiController
{
    public function __construct(private PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
        $this->model = new Prescription;
        parent::__construct($prescriptionService);
    }

    public function getPrescriptions(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');
            $status = $request->query->get('status');
            $fromDate = $request->query->get('startDate');
            $toDate = $request->query->get('endDate');

            $result = $this->prescriptionService->search(
                $offset,
                $limit,
                $sortOrder,
                $sortBy,
                $search,
                $status,
                $fromDate,
                $toDate
            );

            $total = $result['total'];
            $prescriptions = $result['prescriptions'];
            assert($prescriptions instanceof AnonymousResourceCollection);

            return $this->successResponse(
                $prescriptions,
                'Prescriptions successfully retrieved!',
                Response::HTTP_OK,
                $total
            );
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createPrescription(PrescriptionRequest $request): JsonResponse
    {
        try {
            $content = $request->safe()->toArray();
            $prescription = $this->prescriptionService->createPrescription($content);

            return $this->successResponse($prescription, class_basename($this->model).' successfully created');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create '.class_basename($this->model));
        }
    }

    public function updatePrescription(int $prescriptionId, PrescriptionRequest $request): JsonResponse
    {
        try {
            $content = $request->safe()->toArray();
            $prescriptionId = $this->prescriptionService->updatePrescription($prescriptionId, $content);

            return $this->successResponse($prescriptionId, class_basename($this->model).' successfully updated');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), 'Resource not found', Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to update the resource');
        }
    }
}
