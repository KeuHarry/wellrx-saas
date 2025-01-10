export interface User {
  id: number;
  firstName: string;
  lastName: string;
  username: string;
  email: string;
  isActive: boolean;
  preferredLang: string;
  role: {
    id: number;
    label: string;
  };
}
