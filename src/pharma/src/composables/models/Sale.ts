import { SaleItem } from './SaleItem';

export interface Sale {
  id: number;
  code: string;
  status: number;
  total: number;
  taxes: number;
  discount: number;
  netDue: number;
  paymentMethod: number;
  isOneTimePayment: boolean;
  customer: {
    id: number;
    name: string;
  } | null;
  prescription: {
    id: number;
    prescriptionCode: string;
  } | null;
  createdAt: string;
  saleItems: SaleItem[];
}
