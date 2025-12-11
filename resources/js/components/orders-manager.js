export default () => ({
    init() {
        console.log("Orders Manager initialized");
    },

    openCreateOrder() {
        console.log("Opening create order modal");
    },

    filterOrders(status) {
        console.log("Filtering orders by:", status);
    },
});
