export interface Insurer {
  id: number;
  name: string;
  taxPayerNumber: string;
  registrationNumber: string;
  creditLimit: number;
  balance: number;
  deposit: number;
  address: string | null;
  neighborhood: string | null;
}
