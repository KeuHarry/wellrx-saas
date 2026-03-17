import { Permission } from './Permission';

export interface Role {
  id: number;
  label: string;
  description: string;
  permissionsCount: number;
  permissions?: Permission[];
}
