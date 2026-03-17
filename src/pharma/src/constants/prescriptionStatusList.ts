import PrescriptionStatus from '@/enums/prescriptionStatus';

export const prescriptionStatusList = [
  { id: 0, value: null, name: 'Select status', class: 'text-slate-500' },
  { id: 1, value: PrescriptionStatus.IN_PROGRESS, name: 'In progress', class: null },
  { id: 2, value: PrescriptionStatus.COMPLETED, name: 'Completed', class: null },
  { id: 2, value: PrescriptionStatus.PAID, name: 'Paid', class: null },
];
