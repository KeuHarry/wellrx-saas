export interface InsurerRequest {
  name: string;
  taxPayerNumber: string;
  registrationNumber: string;
  address: string | null;
  neighborhood: string | null;
}
