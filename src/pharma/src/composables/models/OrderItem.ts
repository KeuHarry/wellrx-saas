export interface OrderItem {
  id: number;
  product: {
    id: number;
    name: string;
    shelf: string;
  };
  quantity: number;
  expiryDate: string;
  price: number;
  discount: number;
  tax: number;
  wholesaleCost: number;
  retailCost: number;
  markup: number;
}
