export interface SaleFragment {
  id: number;
  code: string;
  status: number;
  cashierStatus: number;
  type: number;
  items: number;
  netDue: number;
  createdAt: string;
  completedDate: string;
}
