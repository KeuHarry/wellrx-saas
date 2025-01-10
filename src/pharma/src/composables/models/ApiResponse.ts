export interface ApiResponse<T> {
  success: boolean;
  message: string;
  total?: number | null;
  data: T | null;
  errors?: Record<string, string[]>;
}
