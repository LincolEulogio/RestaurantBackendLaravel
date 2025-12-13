import { formatMoney } from "./utils/money";
import "./bootstrap";

import Alpine from "alpinejs";
import Chart from "chart.js/auto";
import { createIcons, icons } from "lucide";

// Libs
import "./libs/sweetalert";
import { initDeleteConfirmation, handleFlashMessages } from "./libs/sweetalert";

// Components
import rolesManager, { roleRow } from "./components/roles-manager";
import staffManager from "./components/staff-manager";
import menuManager from "./components/menu-manager";
import blogManager from "./components/blog-manager";
import categoryManager from "./components/category-manager";
import inventoryManager from "./components/inventory-manager";
import billingManager from "./components/billing-manager";
import reportsManager from "./components/reports-manager";

window.Chart = Chart;
window.Alpine = Alpine;
window.formatMoney = formatMoney;
window.menuManager = menuManager;
window.inventoryManager = inventoryManager;
window.billingManager = billingManager;
window.reportsManager = reportsManager;
window.createIcons = createIcons;
window.icons = icons;

// Register Alpine Components
Alpine.data("rolesManager", rolesManager);
Alpine.data("roleRow", roleRow);
Alpine.data("staffManager", staffManager);
Alpine.data("menuManager", menuManager);
Alpine.data("blogManager", blogManager);
Alpine.data("categoryManager", categoryManager);
Alpine.data("inventoryManager", inventoryManager);
Alpine.data("billingManager", billingManager);
Alpine.data("reportsManager", reportsManager);

Alpine.start();

// Initialize Lucide Icons
createIcons({ icons });

// Initialize Global Handlers
initDeleteConfirmation();
handleFlashMessages();
