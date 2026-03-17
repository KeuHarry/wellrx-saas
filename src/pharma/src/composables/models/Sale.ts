import { SaleItem } from './SaleItem';
import { SalePayment } from './SalePayment';

export interface Sale {
  id: number;
  code: string;
  status: number;
  total: number;
  taxes: number;
  discount: number;
  netDue: number;
  paidAmount: number;
  paymentMethod: number;
  isOneTimePayment: boolean;
  paymentDateLimit: string;
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
  payments: SalePayment[];
}
