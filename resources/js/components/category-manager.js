export default () => ({
    openModal: false,
    categories: [],
    form: {
        id: null,
        name: "",
        description: "",
        slug: "",
        is_active: true,
    },
    isEdit: false,

    init() {
        this.fetchCategories();
    },

    async fetchCategories() {
        try {
            const response = await axios.get("/categories");
            if (response.data) {
                this.categories = response.data;
            }
        } catch (error) {
            console.error("Error fetching categories:", error);
            Toast.fire({
                icon: "error",
                title: "Error al cargar categorías",
            });
        }
    },

    openCreateModal() {
        this.resetForm();
        this.isEdit = false;
        this.openModal = true;
    },

    editCategory(category) {
        this.form = {
            id: category.id,
            name: category.name,
            description: category.description,
            slug: category.slug,
            is_active: category.is_active,
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
            name: "",
            description: "",
            slug: "",
            is_active: true,
        };
    },

    generateSlug() {
        this.form.slug = this.form.name
            .toLowerCase()
            .replace(/[^\w ]+/g, "")
            .replace(/ +/g, "-");
    },

    async saveCategory() {
        try {
            let response;
            if (this.isEdit) {
                response = await axios.put(
                    `/categories/${this.form.id}`,
                    this.form
                );
            } else {
                response = await axios.post("/categories", this.form);
            }

            if (response.data.success) {
                await this.fetchCategories();
                this.closeModal();
                Toast.fire({
                    icon: "success",
                    title: this.isEdit
                        ? "Categoría actualizada"
                        : "Categoría creada",
                });
            }
        } catch (error) {
            console.error("Error saving category:", error);
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

    currentPage: 1,
    itemsPerPage: 10,

    get paginatedCategories() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.categories.slice(start, end);
    },

    get totalPages() {
        return Math.ceil(this.categories.length / this.itemsPerPage);
    },

    changePage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
        }
    },

    deleteCategory(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción no se puede revertir. Los productos asociados quedarán sin categoría.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/categories/${id}`);
                    await this.fetchCategories();
                    Toast.fire({
                        icon: "success",
                        title: "Categoría eliminada",
                    });
                } catch (error) {
                    console.error("Error deleting category:", error);
                    Toast.fire({
                        icon: "error",
                        title: "Error al eliminar",
                    });
                }
            }
        });
    },
});
