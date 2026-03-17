import useTenantStore from '@/stores/tenant';

export const usePrintPrescriptionLabels = () => {
  const printLabels = async (prescriptionId: number) => {
    try {
      const tenantStore = useTenantStore();
      const token = sessionStorage.getItem('authToken');
      const response = await fetch(
        `${tenantStore.getTenantUrl}/labels/prescription/${prescriptionId}/print`,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
      );
      if (!response.ok) {
        throw new Error('Failed to fetch labels');
      }
      const html = await response.text();
      const blob = new Blob([html], { type: 'text/html' });
      const url = URL.createObjectURL(blob);
      const printWindow = window.open(url, 'printLabels', 'width=800,height=600');
      if (printWindow) {
        printWindow.focus();
      }
    } catch (error) {
      console.error('Error fetching prescription labels', error);
      throw error;
    }
  };

  return {
    printLabels,
  };
};
