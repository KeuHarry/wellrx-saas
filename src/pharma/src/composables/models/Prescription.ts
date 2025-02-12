import { PrescriptionItem } from './PrescriptionItem';

export interface Prescription {
  id: number;
  prescriptionCode: string;
  patient: {
    id: number;
    firstName: string;
    lastName: string;
    dob: string;
    phone: string;
    address: string;
    city: string;
    company: string;
  };
  prescriber: {
    id: number;
    firstName: string;
    lastName: string;
  };
  total: number;
  netDue: number;
  status: number;
  prescriptionDate: string;
  prescriptionExpDate: string;
  discountPercentage: number;
  discountFlat: number;
  insurancePercentage: number;
  insuranceFlat: number;
  prescriptionItems: PrescriptionItem[];
}
