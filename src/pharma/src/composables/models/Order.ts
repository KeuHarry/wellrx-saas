import { OrderItem } from './OrderItem';

export interface Order {
  id: number;
  invoiceCode: string;
  supplier: {
    id: number;
    name: string;
  };
  invoiceDate: string;
  status: number;
  subTotal: number;
  taxTotal: number;
  total: number;
  charges: number;
  comment: string;
  orderItems: OrderItem[];
}
