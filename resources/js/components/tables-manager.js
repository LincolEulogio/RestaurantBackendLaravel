export default () => ({
    openModal: false,
    tables: [],
    form: {
        id: null,
        table_number: "",
        capacity: 4,
        location: "Indoor",
        status: "available",
    },
    isEdit: false,

    currentPage: 1,
    itemsPerPage: 10,

    init() {
        this.fetchTables();
    },

    async fetchTables() {
        try {
            const response = await axios.get("/tables");
            if (response.data) {
                this.tables = response.data;
            }
        } catch (error) {
            console.error("Error fetching tables:", error);
            Toast.fire({
                icon: "error",
                title: "Error al cargar mesas",
            });
        }
    },

    get paginatedTables() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.tables.slice(start, end);
    },

    get totalPages() {
        return Math.ceil(this.tables.length / this.itemsPerPage);
    },

    get totalStats() {
        return {
            total: this.tables.length,
            totalCapacity: this.tables.reduce(
                (sum, table) => sum + parseInt(table.capacity),
                0
            ),
            available: this.tables.filter((t) => t.status === "available")
                .length,
            reserved: this.tables.filter((t) => t.status === "reserved").length,
            maintenance: this.tables.filter((t) => t.status === "maintenance")
                .length,
        };
    },

    changePage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
        }
    },

    openCreateModal() {
        this.resetForm();
        this.isEdit = false;
        this.openModal = true;
    },

    editTable(table) {
        this.form = {
            id: table.id,
            table_number: table.table_number,
            capacity: table.capacity,
            location: table.location,
            status: table.status,
        };
        this.isEdit = true;
        this.openModal = true;
    },

    closeModal() {
        this.openModal = false;
        this.resetForm();
    },

    resetForm() {
        this.form = {
            id: null,
            table_number: "",
            capacity: 4,
            location: "Indoor",
            status: "available",
        };
    },

    async saveTable() {
        try {
            let response;
            if (this.isEdit) {
                response = await axios.put(
                    `/tables/${this.form.id}`,
                    this.form
                );
            } else {
                response = await axios.post("/tables", this.form);
            }

            if (response.data.success) {
                await this.fetchTables();
                this.closeModal();
                Toast.fire({
                    icon: "success",
                    title: this.isEdit ? "Mesa actualizada" : "Mesa creada",
                });
            }
        } catch (error) {
            console.error("Error saving table:", error);
            if (error.response && error.response.status === 422) {
                const errors = Object.values(error.response.data.errors)
                    .flat()
                    .join("\n");
                Toast.fire({
                    icon: "error",
                    title: "Error de validación",
                    text: errors,
                });
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Error al guardar",
                });
            }
        }
    },

    deleteTable(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción no se puede revertir.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/tables/${id}`);
                    await this.fetchTables();
                    Toast.fire({
                        icon: "success",
                        title: "Mesa eliminada",
                    });
                } catch (error) {
                    console.error("Error deleting table:", error);
                    Toast.fire({
                        icon: "error",
                        title: "Error al eliminar",
                    });
                }
            }
        });
    },
});
