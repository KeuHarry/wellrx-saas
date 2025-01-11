import { SearchParams } from '@/composables/models/SearchParams';
import { FIRSTNAME } from '@/constants/properties';
import SortOrder from '@/enums/sortOrder';
import { defineStore } from 'pinia';
import useToastStore from './toast';
import { User } from '@/composables/models/User';
import { UserRequest } from '@/composables/models/UserRequest';
import useDeleteUser from '@/composables/api/users/useDeleteUser';
import useUpdateUser from '@/composables/api/users/useUpdateUser';
import useCreateUser from '@/composables/api/users/useCreateUser';
import useGetUsers from '@/composables/api/users/useGetUsers';

type UserState = {
  users: User[];
  userTotal: number;
  user: User | null;
  searchParams: SearchParams;
  isPending: boolean;
};

const toastStore = useToastStore();

export const useUserStore = defineStore('user-store', {
  state: (): UserState => ({
    users: [],
    userTotal: 0,
    user: null,
    searchParams: {
      search: '',
      sortBy: FIRSTNAME,
      sortOrder: SortOrder.ASC,
      offset: 0,
      limit: 10,
    },
    isPending: false,
  }),

  getters: {
    getUsers: (state) => state.users,
    getUser: (state) => state.user,
    getUserTotal: (state) => state.userTotal,
  },

  actions: {
    async getUsersApi(searchParams : SearchParams | null = null) {
      const getUsers = useGetUsers(searchParams ? searchParams : this.searchParams);
      try {
        this.isPending = true;
        const response = await getUsers();
        this.users = response.data ?? [];
        this.userTotal = response.total ?? 0;
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async createUserApi(userRequest: UserRequest) {
      const createUser = useCreateUser();
      try {
        this.isPending = true;
        const response = await createUser(userRequest);
        this.user = response.data;
        toastStore.setMessage('User successfully created!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async updateUserApi(id: number, userRequest: UserRequest) {
      const udpateUser = useUpdateUser();
      try {
        this.isPending = true;
        const response = await udpateUser(id, userRequest);
        toastStore.setMessage('User successfully updated!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
    async deleteUserApi(id: number) {
      const deleteUser = useDeleteUser();
      try {
        this.isPending = true;
        const response = await deleteUser(id);
        if (response.success) {
          this.users = this.users.filter((user) => user.id !== id);
        }
        toastStore.setMessage('User successfully deleted!');
        return response;
      } catch (error) {
        this.isPending = false;
        throw error;
      }
    },
  },
});

export default useUserStore;
