import { SaleItemRequest } from './SaleItemRequest';

export interface SaleRequest {
  saleId: number | null;
  status: number;
  total: number;
  taxes: number;
  discount: number;
  netDue: number;
  saleItems: SaleItemRequest[];
}
