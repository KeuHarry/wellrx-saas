import { AuthUser } from './AuthUser';

export interface AuthResponse {
  user: AuthUser;
  token: string;
}
