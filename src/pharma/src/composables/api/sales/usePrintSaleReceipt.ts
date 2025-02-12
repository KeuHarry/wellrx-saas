import useTenantStore from '@/stores/tenant';

export const usePrintSaleReceipt = () => {
  const printPreview = async (id: number) => {
    try {
      const tenantStore = useTenantStore();
      const token = sessionStorage.getItem('authToken');
      const response = await fetch(`${tenantStore.getTenantUrl}/receipt/sale/${id}/print`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      if (!response) {
        throw new Error('Failed to fetch print receipt');
      }

      const blob = await response.blob();
      const url = URL.createObjectURL(blob);

      const printWindow = window.open(url, 'printWindow', 'width=800,height=600');

      if (printWindow) {
        printWindow.focus();
        printWindow.print();
      }
    } catch (error) {
      console.error('Error fetching receipt', error);
    }
  };

  return {
    printPreview,
  };
};
