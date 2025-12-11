export default () => ({
    init() {
        console.log("Kitchen Manager initialized");
    },

    startPreparation(orderId) {
        // Logic to start preparation (AJAX)
        console.log("Starting preparation for order:", orderId);
    },

    markReady(orderId) {
        // Logic to mark as ready (AJAX)
        console.log("Marking ready:", orderId);
    },
});
