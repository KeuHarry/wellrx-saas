export interface UserRequest {
  firstName: string;
  lastName: string;
  username: string;
  email: string;
  isActive: boolean;
  preferredLang: string;
  roleId: number | null;
}
