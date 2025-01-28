export interface CashRegister {
  id: number;
  openedBy: number;
  status: number;
  createdAt: string;
  amount: number;
  closedBy: number;
  closingTime: string;
}
