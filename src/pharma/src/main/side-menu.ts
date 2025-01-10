import { type Menu } from '@/stores/menu';

const menu: Array<Menu | 'divider'> = [
  {
    icon: 'Activity',
    pageName: 'dashboard-overview-1',
    title: 'Dashboard',
  },
  {
    icon: 'DatabaseIcon',
    pageName: 'administration',
    title: 'Administration',
    subMenu: [
      {
        icon: 'UsersIcon',
        pageName: 'user-list',
        title: 'Users',
      },
      {
        icon: 'LayersIcon',
        pageName: 'role-list',
        title: 'Roles & Permissions',
      },
      {
        icon: 'SettingsIcon',
        pageName: 'settings',
        title: 'Settings',
      },
    ],
  },
  {
    icon: 'PillIcon',
    pageName: 'products-management',
    title: 'Products',
    subMenu: [
      {
        icon: 'UsersIcon',
        pageName: 'category-list',
        title: 'Categories',
      },
      {
        icon: 'LayersIcon',
        pageName: 'shelf-list',
        title: 'Shelves',
      },
      {
        icon: 'PillIcon',
        pageName: 'product-list',
        title: 'Products',
      },
      {
        icon: 'BanknoteIcon',
        pageName: 'fee-list',
        title: 'Fees / Taxes',
      },
    ],
  },
  {
    icon: 'ShoppingCart',
    pageName: 'point-of-sale',
    title: 'Point of Sale',
    subMenu: [
      {
        icon: 'CreditCard',
        pageName: 'cashier',
        title: 'Cashier',
      },
      {
        icon: 'ShoppingCart',
        pageName: 'sale',
        title: 'Sales',
      },
      {
        icon: 'SquareStack',
        pageName: 'cash-register',
        title: 'Cash Register',
      },
      {
        icon: 'User',
        pageName: 'customer-list',
        title: 'Customers',
      },
    ],
  },
  {
    icon: 'Archive',
    pageName: 'stock-management',
    title: 'Stock',
    subMenu: [
      {
        icon: 'Boxes',
        pageName: 'inventory',
        title: 'Inventory',
      },
      {
        icon: 'Truck',
        pageName: 'supplier-list',
        title: 'Suppliers',
      },
      {
        icon: 'FileText',
        pageName: 'order',
        title: 'Orders',
      },
    ],
  },
  {
    icon: 'Clipboard',
    pageName: 'prescription',
    title: 'Prescriptions',
    subMenu: [
      {
        icon: 'Clipboard',
        pageName: 'prescription',
        title: 'Prescriptions',
      },
      {
        icon: 'User',
        pageName: 'patient-list',
        title: 'Patients',
      },
      {
        icon: 'Stethoscope',
        pageName: 'prescriber-list',
        title: 'Doctors',
      },
      {
        icon: 'User',
        pageName: 'intermediary-list',
        title: 'Intermediaries',
      },
      {
        icon: 'Tags',
        pageName: 'labelcode-list',
        title: 'Labels',
      },
    ],
  },
  {
    icon: 'Shield',
    pageName: 'insurance',
    title: 'Insurance',
    subMenu: [
      {
        icon: 'Briefcase',
        pageName: 'insurer-list',
        title: 'Insurers',
      },
    ],
  },
  'divider',
  {
    icon: 'FileLineChart',
    pageName: 'report',
    title: 'Reports',
    subMenu: [
      {
        icon: 'Activity',
        pageName: 'sales-report',
        title: 'Sales Report',
      },
      {
        icon: 'Activity',
        pageName: 'cash-register-report',
        title: 'Cash Register Report',
      },
      {
        icon: 'Activity',
        pageName: 'profit-and-loss',
        title: 'Profit and Loss Report',
      },
      {
        icon: 'Activity',
        pageName: 'stock-report',
        title: 'Stock Report',
      },
    ],
  },
];

export default menu;
