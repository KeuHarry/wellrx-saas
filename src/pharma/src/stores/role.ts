import { SearchParams } from '@/composables/models/SearchParams';
import { LABEL } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { Role } from '@/composables/models/Role';
import useCreateRole from '@/composables/api/roles/useCreateRole';
import { RoleRequest } from '@/composables/models/RoleRequest';
import useGetRoles from '@/composables/api/roles/useGetRoles';
import useUpdateRole from '@/composables/api/roles/useUpdateRole';
import useDeleteRole from '@/composables/api/roles/useDeleteRole';
import useGetPermissions from '@/composables/api/roles/useGetPermissions';
import { Permission } from '@/composables/models/Permission';
import useGetRole from '@/composables/api/roles/useGetRole';
import { AssignPermissionRequest } from '@/composables/models/AssignPermissionRequest';
import useAssignPermissions from '@/composables/api/roles/useAssignPermissions';

type RoleState = {
  roles: Role[];
  role: Role | null;
  searchParams: SearchParams;
  isPending: boolean;
  roleTotal: number;
  permissions: Permission[];
  permissionTotal: number;
  rolePermissions: Permission[];
};

const toastStore = useToastStore();

export const useRoleStore = defineStore('role-store', {
  state: (): RoleState => ({
    roles: [],
    role: null,
    searchParams: {
      search: '',
      sortBy: LABEL,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
    roleTotal: 0,
    permissions: [],
    permissionTotal: 0,
    rolePermissions: [],
  }),

  getters: {
    getRoles: (state) => state.roles,
    getRole: (state) => state.role,
    getRoleTotal: (state) => state.roleTotal,
    getPermissions: (state) => state.permissions,
    getPermissionTotal: (state) => state.permissionTotal,
    getRolePermissions: (state) => state.rolePermissions,
  },

  actions: {
    async getRolesApi(searchParams: SearchParams | null) {
      const getRoles = useGetRoles(searchParams ? searchParams :  this.searchParams);
      try {
        this.isPending = true;
        const response = await getRoles();
        this.roles = response.data ?? [];
        this.roleTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createRoleApi(roleRequest: RoleRequest) {
      const createRole = useCreateRole();
      try {
        this.isPending = true;
        const response = await createRole(roleRequest);
        this.role = response.data;
        toastStore.setMessage('Role successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateRoleApi(id: number, roleRequest: RoleRequest) {
      const udpateRole = useUpdateRole();
      try {
        this.isPending = true;
        const response = await udpateRole(id, roleRequest);
        toastStore.setMessage('Role successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteRoleApi(id: number) {
      const deleteRole = useDeleteRole();
      try {
        this.isPending = true;
        const response = await deleteRole(id);
        if (response.success) {
          this.roles = this.roles.filter((role) => role.id !== id);
          toastStore.setMessage('Role successfully deleted!');
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getPermissionsApi() {
      const getPermissions = useGetPermissions();
      try {
        this.isPending = true;
        const response = await getPermissions();
        this.permissions = response.data ?? [];
        this.permissionTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async getRoleApi(id: number) {
      const getRole = useGetRole();
      try {
        this.isPending = true;
        const response = await getRole(id);
        if (response.success) {
          this.role = response.data;
          this.rolePermissions = response.data?.permissions ?? [];
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async assignPermissionsApi(roleRequest: AssignPermissionRequest) {
      const assignPermissions = useAssignPermissions();
      try {
        this.isPending = true;
        const response = await assignPermissions(roleRequest);
        if (response.success) {
          toastStore.setMessage(`${this.role?.label} permissions successfully upated!`);
          //await this.getRolesApi();
        }
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useRoleStore;
