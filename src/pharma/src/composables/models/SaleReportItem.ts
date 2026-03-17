export interface SaleReportItem {
  id: number;
  paidDate: string;
  salesman: string;
  cashier: string;
  product: string;
  quantityBefore: number;
  quantityAfter: number;
  quantitySold: number;
  qoh: number;
  price: number;
  saleItemTotal: number;
}
