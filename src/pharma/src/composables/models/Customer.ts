export interface Customer {
  id: number;
  name: string;
  email: string | null;
  creditLimit: number;
  purchasedTotal: number;
  balance: number;
  deposit: number;
  phone: string;
  address: string | null;
  city: string | null;
}
