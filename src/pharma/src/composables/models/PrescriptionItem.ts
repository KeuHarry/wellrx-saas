export interface PrescriptionItem {
  id: number;
  product: {
    id: number;
    name: string;
  };
  quantity: number;
  courseDays: number;
  refillAllowedTimes: number;
  fee: number;
  tax: number;
  cost: number;
  total: number;
}
