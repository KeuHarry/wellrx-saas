import { OrderItemRequest } from './OrderItemRequest';

export interface OrderRequest {
  invoiceCode: string;
  supplierId: number;
  invoiceDate: string;
  status: number;
  subTotal: number;
  taxTotal: number;
  total: number;
  charges: number;
  comment: string;
  orderItems: OrderItemRequest[];
}
