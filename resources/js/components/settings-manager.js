export default () => ({
    activeTab: localStorage.getItem("settingsActiveTab") || "general",
    loading: false,

    init() {
        this.$watch("activeTab", (val) =>
            localStorage.setItem("settingsActiveTab", val)
        );
    },

    updateTab(tab) {
        this.activeTab = tab;
    },

    async toggleStatus(url) {
        if (this.loading) return null;
        this.loading = true;

        try {
            const response = await fetch(url, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            if (response.ok) {
                const data = await response.json();
                return data.is_active; // Return new status
            } else {
                console.error("Failed to toggle status");
                return null;
            }
        } catch (e) {
            console.error("Error toggling status:", e);
            return null;
        } finally {
            this.loading = false;
        }
    },
});
