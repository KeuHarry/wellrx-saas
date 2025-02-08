import { InStockInventory } from './InStockInventory';

export interface InStockProduct {
  id: number;
  name: string;
  qoh: number;
  wholesaleCost: number;
  retailCost: number;
  tax: {
    id: number;
    value: number;
    type: number;
  } | null;
  shelf: string;
  inventories: InStockInventory[];
}
