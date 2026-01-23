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
        image_url: null,
        image_preview: null,
        is_available: true,
    },
    isEdit: false,
    currentFilter: "Todos",

    init() {
        this.fetchCategories();
        this.fetchProducts();
        console.log("Menu Manager initialized");
    },

    handleImageError(event, product) {
        // Set image_url to null so Alpine shows the placeholder automatically
        if (product) {
            product.image_url = null;
        }
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
            // Add timestamp to avoid cache issues
            const timestamp = Date.now();
            const response = await axios.get(`/menu?_t=${timestamp}`);
            this.products = response.data;
            console.log("Products loaded:", this.products.length);
        } catch (error) {
            console.error("Error fetching products:", error);
            Toast.fire({
                icon: "error",
                title: "Error al cargar el menú",
            });
        }
    },

    currentPage: 1,
    itemsPerPage: 10,

    get paginatedProducts() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredProducts.slice(start, end);
    },

    get totalPages() {
        return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
    },

    changePage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
        }
    },

    get filteredProducts() {
        let result = [];
        if (this.currentFilter === "Todos") {
            result = this.products;
        } else {
            result = this.products.filter(
                (p) => p.category && p.category.name === this.currentFilter,
            );
        }
        return result;
    },

    setFilter(categoryName) {
        this.currentFilter = categoryName;
        this.currentPage = 1; // Reset to first page when filtering
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
            image_url: product.image_url,
            image_preview: null,
            is_available: product.is_available,
        };
        this.isEdit = true;
        this.openModal = true;
    },

    closeModal() {
        this.openModal = false;
        setTimeout(() => this.resetForm(), 300);
    },

    resetForm() {
        this.form = {
            id: null,
            name: "",
            price: "",
            category_id: "",
            description: "",
            image: null,
            image_url: null,
            image_preview: null,
            is_available: true,
        };
        const fileInput = document.getElementById("dropzone-file");
        if (fileInput) fileInput.value = "";
    },

    handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file type
        const allowedTypes = [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/gif",
            "image/webp",
        ];
        if (!allowedTypes.includes(file.type)) {
            Toast.fire({
                icon: "error",
                title: "Tipo de archivo no válido",
                text: "Solo se permiten imágenes (JPEG, PNG, GIF, WebP)",
            });
            return;
        }

        // Validate file size (max 10MB)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            Toast.fire({
                icon: "error",
                title: "Archivo demasiado grande",
                text: "El tamaño máximo permitido es 10MB",
            });
            return;
        }

        this.form.image = file;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.form.image_preview = e.target.result;
        };
        reader.onerror = () => {
            Toast.fire({
                icon: "error",
                title: "Error al procesar la imagen",
            });
        };
        reader.readAsDataURL(file);
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
                // Clear cache to ensure frontend gets fresh data
                await this.fetchProducts();
                this.closeModal();
                Toast.fire({
                    icon: "success",
                    title: this.isEdit
                        ? "Producto actualizado correctamente"
                        : "Producto creado correctamente",
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
                        error.response.data.errors,
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
                    const response = await axios.delete(`/menu/${id}`);
                    if (response.data.success) {
                        await this.fetchProducts();
                        Toast.fire({
                            icon: "success",
                            title: "Producto eliminado correctamente",
                        });
                    }
                } catch (error) {
                    console.error("Error deleting product:", error);
                    let errorMessage = "Error al eliminar el producto";

                    if (error.response && error.response.data) {
                        errorMessage =
                            error.response.data.message || errorMessage;
                    }

                    Toast.fire({
                        icon: "error",
                        title: "Error al eliminar",
                        text: errorMessage,
                    });
                }
            }
        });
    },
});
