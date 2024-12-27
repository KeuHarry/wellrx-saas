export interface RegistrationResponse {
  success: string
  tenant: {
    id: String
    identifier: string
    account: string
  }
}
