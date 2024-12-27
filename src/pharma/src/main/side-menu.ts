import { type Menu } from '@/stores/menu'

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
        pageName: 'user-accounts',
        title: 'Users',
      },
      {
        icon: 'LayersIcon',
        pageName: 'user-roles',
        title: 'User Roles',
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
        pageName: 'products',
        title: 'Products',
      },
      {
        icon: 'BanknoteIcon',
        pageName: 'fees',
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
        pageName: 'customer',
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
        pageName: 'supplier',
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
        pageName: 'patient',
        title: 'Patients',
      },
      {
        icon: 'Stethoscope',
        pageName: 'doctor',
        title: 'Doctors',
      },
      {
        icon: 'User',
        pageName: 'intermediary',
        title: 'Intermediaries',
      },
      {
        icon: 'Tags',
        pageName: 'label',
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
        pageName: 'insurer',
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
]

export default menu
