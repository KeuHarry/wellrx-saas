export interface InventoryRequest {
  productId: number;
  quantity: number;
  type: number;
  expiryDate: string;
  batchId: string;
  acqCost: number;
  wholesaleCost: number;
  retailCost: number;
  unitCost: number;
  supplierId: number;
  reason: string;
}
