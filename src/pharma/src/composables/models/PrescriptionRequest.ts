import { PrescriptionItemRequest } from './PrescriptionItemRequest';

export interface PrescriptionRequest {
  patientId: number;
  prescriberId: number;
  total: number;
  taxes: number;
  netDue: number;
  status: number;
  prescriptionDate: string;
  prescriptionExpDate: string;
  discountPercentage: number;
  discountFlat: number;
  insurancePercentage: number;
  insuranceFlat: number;
  prescriptionItems: PrescriptionItemRequest[];
}
