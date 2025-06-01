export interface Inventory {
  id: number;
  product: {
    id: number;
    label: string;
    shelf: string;
  };
  quantity: number;
  batchQoh?: number;
  type: number;
  expiryDate: string;
  batchId: number;
  acqCost: number;
  wholesaleCost: number;
  retailCost: number;
  unitCost: number;
  supplier: {
    id: number;
    name: string;
  };
  reason: string | null;
  createdAt: string;
}
