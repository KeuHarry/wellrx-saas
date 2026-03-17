export interface ProductRequest {
  label: string;
  isPrescription: boolean;
  isGeneric: boolean;
  genericProductId: number | null;
  barcode: string | null;
  cipCode: string | null;
  acqCost: number;
  wholesaleCost: number;
  retailCost: number;
  unitCost: number;
  markup: number | null;
  description: string | null;
  qoh: number;
  minQuantity: number | null;
  maxQuantity: number | null;
  isDecomposable: boolean;
  isActive: boolean;
  pkgSize: number;
  shelfId: number;
  productCategoryId: number;
  feeId: number | null;
  labelCodeIds: number[] | null;
}
