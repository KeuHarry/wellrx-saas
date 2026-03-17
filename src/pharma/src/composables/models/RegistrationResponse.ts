export interface RegistrationResponse {
  success: string;
  tenant: {
    id: string;
    identifier: string;
    account: string;
  };
}
