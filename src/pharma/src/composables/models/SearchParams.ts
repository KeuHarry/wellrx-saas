export interface SearchParams {
  offset: number;
  limit: number;
  search?: string | null;
  tag?: number | null;
  sortBy?: string;
  sortOrder?: string;
  shelfId?: number | null;
  type?: number | null;
}
