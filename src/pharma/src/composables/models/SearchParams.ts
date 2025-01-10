export interface SearchParams {
  search?: string | null;
  tag?: number | null;
  sortBy?: string;
  sortOrder?: string;
  offset: number;
  limit: number;
}
