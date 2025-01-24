export interface Inventory {
  id: number;
  product: {
    id: number;
    label: string;
    shelf: string;
  } | null;
  quantity: number;
  type: number;
  expiryDate: string;
  batchId: string;
  acqCost: number;
  wholesaleCost: number;
  retailCost: number;
  unitCost: number;
  supplierId: number;
  reason: string|null;
  createdAt: string;
}
