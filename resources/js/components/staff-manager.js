export default () => ({
    openModal: false,
    isEdit: false,
    form: { id: null, name: "", email: "", role: "waiter", password: "" },

    openCreate() {
        this.isEdit = false;
        this.form = {
            id: null,
            name: "",
            email: "",
            role: "waiter",
            password: "",
        };
        this.openModal = true;
    },

    openEdit(user) {
        this.isEdit = true;
        this.form = {
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role,
            password: "",
        };
        this.openModal = true;
    },

    // Generates the correct action URL for the form
    getActionUrl(baseUrl) {
        return this.isEdit ? `${baseUrl}/${this.form.id}` : baseUrl; // store route
    },
});
