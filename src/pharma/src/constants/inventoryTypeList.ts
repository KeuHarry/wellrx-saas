import InventoryType from '@/enums/inventoryType';

export const inventoryTypeList = [
  { id: 0, value: null, name: 'Select type', class: 'text-slate-500' },
  { id: 1, value: InventoryType.IN, name: 'In', class: null },
  { id: 2, value: InventoryType.SELL, name: 'Sell', class: null },
  { id: 3, value: InventoryType.RETURN, name: 'Return', class: null },
  { id: 4, value: InventoryType.SHIPMENT, name: 'Shipment', class: null },
  { id: 5, value: InventoryType.REFUND, name: 'Refund', class: null },
  { id: 6, value: InventoryType.CORRECTION, name: 'Correction', class: null },
  { id: 7, value: InventoryType.DECOMPOSED, name: 'Decomposed', class: null },
];
