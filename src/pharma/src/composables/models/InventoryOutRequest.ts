export interface InventoryOutRequest {
  inventoryId: number | null;
  quantity: number;
  type: number;
  reason: string | null;
}
