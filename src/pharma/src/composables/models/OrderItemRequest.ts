export interface OrderItemRequest {
  productId: number;
  quantity: number;
  expiryDate: string;
  price: number;
  discount: number;
  tax: number;
  wholesaleCost: number;
  retailCost: number;
  markup: number;
}
