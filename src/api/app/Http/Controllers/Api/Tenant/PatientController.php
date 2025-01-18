<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Patient;
use App\Services\PatientService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PatientController extends ApiController
{
    private PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
        $this->model = new Patient();
        parent::__construct($patientService);
    }

    public function getPatients(SearchRequest $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $users = $this->patientService->search(
                $offset,
                $limit,
                $sortOrder,
                $search,
                $sortBy
            );

            return $this->successResponse($users, class_basename($this->model).'s successfully retrieved!', Response::HTTP_OK, $this->patientService->getModelCount());
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to get resources');
        }
    }

    public function createPatient(PatientRequest $request): JsonResponse
    {

        return $this->store($request);
    }

    public function updatePatient(PatientRequest $request, int $patientId): JsonResponse
    {
        return $this->update($patientId, $request);
    }
}
