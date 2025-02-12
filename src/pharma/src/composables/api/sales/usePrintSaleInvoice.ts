import useTenantStore from '@/stores/tenant';

export const usePrintSaleInvoice = () => {
  const printPreview = async (id: number) => {
    try {
      const tenantStore = useTenantStore();
      const token = sessionStorage.getItem('authToken');
      const response = await fetch(`${tenantStore.getTenantUrl}/invoice/sale/${id}/print`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      if (!response) {
        throw new Error('Failed to fetch print data');
      }

      const blob = await response.blob();
      const url = URL.createObjectURL(blob);

      const printWindow = window.open(url, 'printWindow', 'width=800,height=600');

      if (printWindow) {
        printWindow.focus();
        printWindow.print();
      }
    } catch (error) {
      console.error('Error fetching invoice', error);
    }
  };

  return {
    printPreview,
  };
};
