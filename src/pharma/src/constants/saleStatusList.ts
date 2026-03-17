import SaleStatus from '@/enums/saleStatus';

export const saleStatusList = [
  { id: 0, value: null, name: 'Select status', class: 'text-slate-500' },
  { id: 1, value: SaleStatus.IN_PROGRESS, name: 'In progress', class: null },
  { id: 2, value: SaleStatus.COMPLETED, name: 'Completed', class: null },
  { id: 2, value: SaleStatus.PAID, name: 'Paid', class: null },
];
