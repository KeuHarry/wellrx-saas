export interface PrescriptionFragment {
  id: number;
  prescriptionCode: string;
  patient: {
    id: number;
    firstName: string;
    lastName: string;
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
}
