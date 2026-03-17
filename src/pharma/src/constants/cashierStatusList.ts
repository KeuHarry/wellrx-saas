import CashierStatus from '@/enums/cashierStatus';

export const cashierStatusList = [
  { id: 0, value: null, name: 'Select status', class: 'text-slate-500' },
  { id: 1, value: CashierStatus.PENDING, name: 'Pending', class: null },
  { id: 2, value: CashierStatus.IN_PROGRESS, name: 'In Progress', class: null },
];
