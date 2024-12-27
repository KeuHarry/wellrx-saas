<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ObjectNotFountException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Tenant\Patient;
use App\Services\PatientService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PatientController extends ApiController
{
    private PatientService $patientService;
    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
        $this->model = new Patient();
        parent::__construct();
    }

    public function createPatient(PatientRequest $request) 
    {
        $content = $request->all();

        $patient = $this->patientService->createPatient($content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'patient' => $patient
        ]);
    }

    public function getPatient(int $patientId)
    {
        $patient = $this->patientService->getPatient($patientId);

        if(! isset($patient)){
            throw new ObjectNotFountException($patientId, 'Patient');
        }

        return $this->successResponse($patient);
    }

    public function getPatients(SearchRequest $request)
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        $patients = $this->patientService->search(
            $search,
            $sortBy,
            $sortOrder,
            $offset,
            $limit
        );

        if (! assert($patients instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'total' => empty($search) ? count($this->model->all()) : count($patients),
            'data' => $patients
        ]);
    }

    public function deletePatient(int $patientId)
    {
        $patient = $this->patientService->getPatient($patientId);

        if(! isset($patient)){
            throw new ObjectNotFountException($patientId, 'Patient');
        }

        $result = $this->patientService->deletePatient($patient->id);
        if(! $result){
            return new JsonResponse([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error. Try again or contact support'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'patient' => $patientId,
            'message' => 'Patient successfully deleted.'
        ]);
    }

    public function updatePatient(PatientRequest $request, int $patientId)
    {
        $patient = $this->patientService->getPatient($patientId);

        if(! isset($patient)){
            throw new ObjectNotFountException($patientId, 'Patient');
        }

        $content = $request->all();

        $updatedPatient = $this->patientService->updatePatient($patientId, $content);

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'patient' => $updatedPatient,
            'message' => 'Insurer successfully updated.'
        ]);
    }
}
