import SaleType from '@/enums/saleType';

export const saleTypeList = [
  { id: 0, value: null, name: 'Select type', class: 'text-slate-500' },
  { id: 1, value: SaleType.SALE, name: 'Sale', class: null },
  { id: 2, value: SaleType.REFUND, name: 'Refund', class: null },
];

export const SALE = 'sale';
export const REFUND = 'refund';
