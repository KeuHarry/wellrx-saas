export interface CashRegister {
  id: number;
  openedBy: string;
  status: number;
  createdAt: string;
  amount: number;
  closedBy: string | null;
  closingTime: string;
}
