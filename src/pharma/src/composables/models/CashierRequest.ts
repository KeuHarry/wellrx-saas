export interface CashierOneTimePaymentRequest {
    saleId: number;
    customerName: string;
    tendered: number;
    paymentMethod: number;
    paymentReference: string;
    isOneTimePayment: boolean;
    reset: () => void;
}
  