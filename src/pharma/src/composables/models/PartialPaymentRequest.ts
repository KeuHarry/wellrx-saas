export interface PartialPaymentRequest {
  saleId: number;
  paymentMethod: number;
  reference: string;
  tendered: number;
  amount: number;
  change: number;
}
