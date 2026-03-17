export interface Patient {
  id: number;
  patientCode: string;
  firstName: string;
  lastName: string;
  dob: string;
  email: string | null;
  phone: string;
  company: string;
  address: string | null;
  city: string;
  insurerId: number | null;
  caretakerId: number | null;
  intermediaryId: number | null;
  allergies: string | null;
  medecineHistory: string | null;
}
