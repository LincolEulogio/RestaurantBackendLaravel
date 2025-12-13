export default () => ({
    openModal: false,
    products: [],
    categories: [],
    form: {
        id: null,
        name: "",
        price: "",
        category_id: "",
        description: "",
        image: null,
        is_available: true,
    },
    isEdit: false,
    currentFilter: "Todos",

    init() {
        this.fetchCategories();
        this.fetchProducts();
        console.log("Menu Manager initialized");
    },

    async fetchCategories() {
        try {
            const response = await axios.get("/categories");
            this.categories = response.data;
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    },

    async fetchProducts() {
        try {
            const response = await axios.get("/menu");
            this.products = response.data;
        } catch (error) {
            console.error("Error fetching products:", error);
            Toast.fire({
                icon: "error",
                title: "Error al cargar el menú",
            });
        }
    },

    get filteredProducts() {
        if (this.currentFilter === "Todos") {
            return this.products;
        }
        return this.products.filter(
            (p) => p.category && p.category.name === this.currentFilter
        );
    },

    setFilter(categoryName) {
        this.currentFilter = categoryName;
    },

    openCreateModal() {
        this.resetForm();
        this.isEdit = false;
        this.openModal = true;
    },

    editProduct(product) {
        this.form = {
            id: product.id,
            name: product.name,
            price: product.price,
            category_id: product.category_id,
            description: product.description,
            image: null,
            is_available: product.is_available,
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
            price: "",
            category_id: "",
            description: "",
            image: null,
            is_available: true,
        };
        const fileInput = document.getElementById("dropzone-file");
        if (fileInput) fileInput.value = "";
    },

    handleFileUpload(event) {
        this.form.image = event.target.files[0];
    },

    async saveProduct() {
        const formData = new FormData();
        formData.append("name", this.form.name);
        formData.append("price", this.form.price);
        formData.append("category_id", this.form.category_id);
        formData.append("description", this.form.description || "");
        formData.append("is_available", this.form.is_available ? 1 : 0);

        if (this.form.image) {
            formData.append("image", this.form.image);
        }

        try {
            let response;
            if (this.isEdit) {
                formData.append("_method", "PUT");
                response = await axios.post(`/menu/${this.form.id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
            } else {
                response = await axios.post("/menu", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
            }

            if (response.data.success) {
                await this.fetchProducts();
                this.closeModal();
                Toast.fire({
                    icon: "success",
                    title: this.isEdit
                        ? "Producto actualizado"
                        : "Producto creado",
                });
            }
        } catch (error) {
            if (error.response) {
                console.error(error.response.data);
                console.error(error.response.data.errors);

                // Show validation errors
                if (
                    error.response.status === 422 &&
                    error.response.data.errors
                ) {
                    const errorMessages = Object.values(
                        error.response.data.errors
                    )
                        .flat()
                        .join("\n");
                    Toast.fire({
                        icon: "error",
                        title: "Error de validación",
                        text: errorMessages,
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Error al guardar producto",
                    });
                }
            } else {
                console.error(error);
                Toast.fire({
                    icon: "error",
                    title: "Error al guardar producto",
                });
            }
        }
    },

    deleteProduct(id) {
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
                    await axios.delete(`/menu/${id}`);
                    await this.fetchProducts();
                    Toast.fire({
                        icon: "success",
                        title: "Producto eliminado",
                    });
                } catch (error) {
                    console.error("Error deleting product:", error);
                    Toast.fire({
                        icon: "error",
                        title: "Error al eliminar",
                    });
                }
            }
        });
    },
});
