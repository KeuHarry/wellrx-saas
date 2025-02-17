export interface SalePayment {
  id: number;
  paymentMethod: number;
  reference: string | null;
  tendered: number;
  amount: number;
  change: number;
  createdAt: string;
}
