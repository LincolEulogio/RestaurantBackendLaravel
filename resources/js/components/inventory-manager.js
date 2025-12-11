export default () => ({
    openModal: false,
    items: [],
    form: {
        id: null,
        name: "",
        sku: "",
        category: "",
        stock_current: 0,
        stock_min: 5,
        unit: "unid", // kg, lt, unid
        price_unit: 0,
        is_active: true,
    },
    isEdit: false,
    searchTerm: "",
    filterCategory: "Todas las categorías",
    filterStatus: "Todos los estados",

    init() {
        this.fetchItems();
    },

    async fetchItems() {
        try {
            const response = await axios.get("/inventory-items"); // We'll set this route up
            this.items = response.data;
        } catch (error) {
            console.error("Error fetching inventory:", error);
            Toast.fire({
                icon: "error",
                title: "Error al cargar inventario",
            });
        }
    },

    get filteredItems() {
        return this.items.filter((item) => {
            const matchesSearch =
                item.name
                    .toLowerCase()
                    .includes(this.searchTerm.toLowerCase()) ||
                item.sku.toLowerCase().includes(this.searchTerm.toLowerCase());

            const matchesCategory =
                this.filterCategory === "Todas las categorías" ||
                item.category === this.filterCategory;

            let matchesStatus = true;
            if (this.filterStatus === "OK") {
                matchesStatus =
                    Number(item.stock_current) > Number(item.stock_min);
            } else if (this.filterStatus === "Bajo Stock") {
                matchesStatus =
                    Number(item.stock_current) <= Number(item.stock_min);
            }

            return matchesSearch && matchesCategory && matchesStatus;
        });
    },

    // Calculated Stats
    get totalItems() {
        return this.items.length;
    },
    get lowStockCount() {
        return this.items.filter(
            (i) => Number(i.stock_current) <= Number(i.stock_min)
        ).length;
    },
    get totalValue() {
        return this.items.reduce(
            (sum, i) => sum + Number(i.stock_current) * Number(i.price_unit),
            0
        );
    },
    get uniqueCategories() {
        return [...new Set(this.items.map((i) => i.category).filter(Boolean))]
            .length;
    },

    // Dynamic Filter Options
    get categoriesList() {
        return [...new Set(this.items.map((i) => i.category).filter(Boolean))];
    },

    openCreateModal() {
        this.resetForm();
        this.isEdit = false;
        this.openModal = true;
    },

    editItem(item) {
        this.form = { ...item };
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
            name: "",
            sku: "",
            category: "",
            stock_current: 0,
            stock_min: 5,
            unit: "unid",
            price_unit: 0,
            is_active: true,
        };
    },

    async saveItem() {
        try {
            let response;
            if (this.isEdit) {
                response = await axios.put(
                    `/inventory-items/${this.form.id}`,
                    this.form
                );
            } else {
                response = await axios.post("/inventory-items", this.form);
            }

            if (response.data.success) {
                await this.fetchItems();
                this.closeModal();
                Toast.fire({
                    icon: "success",
                    title: this.isEdit ? "Insumo actualizado" : "Insumo creado",
                });
            }
        } catch (error) {
            console.error("Error saving item:", error);
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

    deleteItem(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esta acción",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/inventory-items/${id}`);
                    await this.fetchItems();
                    Toast.fire({
                        icon: "success",
                        title: "Insumo eliminado",
                    });
                } catch (error) {
                    console.error("Error deleting item:", error);
                    Toast.fire({
                        icon: "error",
                        title: "Error al eliminar",
                    });
                }
            }
        });
    },
});
