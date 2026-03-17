import { useSetupAxios } from '../useAxiosTenant';
import useTenantStore from '@/stores/tenant';

export default function useExportProducts(): () => Promise<void> {
  const axiosTenant = useSetupAxios();
  const tenantStore = useTenantStore();

  return async () => {
    const response = await axiosTenant.get('/products/export/csv', {
      responseType: 'blob',
    });
    const blob = response.data as Blob;
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'products.csv';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
  };
}
