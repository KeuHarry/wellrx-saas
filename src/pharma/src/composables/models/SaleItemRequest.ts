export interface SaleItemRequest {
  productId: number;
  productName: string;
  quantity: number;
  price: number;
  tax: number | null;
  total: number;
  prescriptionItemId?: number | null;
  shelf?: string;
}
