export interface SaleItem {
  id: number;
  product: {
    id: number;
    name: string;
    shelf: string;
    qoh: number;
  };
  quantity: number;
  price: number;
  tax: number;
  fee: number | null;
  total: number;
}
