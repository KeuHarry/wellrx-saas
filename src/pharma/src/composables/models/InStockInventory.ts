export interface InStockInventory {
  id: number;
  batchId: number;
  type: number;
  actualQuantity: number;
  expiryDate: string;
  acqCost: number;
}
