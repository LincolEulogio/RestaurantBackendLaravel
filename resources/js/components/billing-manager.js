export default () => ({
    selectedOrderId: null,
    selectedOrder: null,
    paymentMethod: null,
    amountReceived: 0,

    init() {
        console.log("Billing Manager initialized");
    },

    get total() {
        return this.selectedOrder ? this.selectedOrder.total : 0;
    },

    get change() {
        if (
            this.paymentMethod === "cash" &&
            this.amountReceived &&
            this.selectedOrder
        ) {
            return Math.max(0, this.amountReceived - this.total);
        }
        return 0;
    },

    async selectOrder(orderId) {
        this.selectedOrderId = orderId;
        this.paymentMethod = null;
        this.amountReceived = 0;

        // Fetch order details
        try {
            const response = await fetch(`/billing/${orderId}/details`);
            const data = await response.json();
            this.selectedOrder = data.order;
            console.log("Order selected:", this.selectedOrder);
        } catch (error) {
            console.error("Error fetching order details:", error);
        }
    },

    formatMoney(amount) {
        return "S/ " + parseFloat(amount || 0).toFixed(2);
    },
});
