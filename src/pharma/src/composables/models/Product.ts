export interface Product {
  id: number;
  productCode: string;
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
  shelf: {
    id: number;
    name: string;
  } | null;
  category: {
    id: number;
    name: string;
  } | null;
  fee: {
    id: number;
    name: string;
    value: number;
  } | null;
  generic: {
    id: number;
    name: string;
  } | null;
  labelCodes?: { id: number; labelCode: string; label: string }[];
}
