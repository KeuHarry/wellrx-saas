export interface PrescriptionItemRequest {
  productId: number;
  productName: string;
  quantity: number;
  courseDays: number;
  refillAllowedTimes: number;
  tax: number;
  fee: number;
  cost: number;
  total: number;
  labelCodes: number[];
}
