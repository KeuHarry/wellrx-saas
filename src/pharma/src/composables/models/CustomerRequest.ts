export interface CustomerRequest {
  name: string;
  email: string | null;
  creditLimit: number;
  balance: number;
  deposit: number;
  phone: string;
  address: string | null;
  city: string | null;
}
