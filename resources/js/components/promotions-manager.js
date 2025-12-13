export default () => ({
    promotions: [],
    availableProducts: [],
    openModal: false,
    isEdit: false,
    currentFilter: "active", // active, inactive, all
    form: {
        id: null,
        title: "",
        description: "",
        discount_percent: "",
        start_date: "",
        end_date: "",
        badge_label: "",
        status: true,
        image: null,
        image_url: null,
        image_preview: null,
        products: [], // Array of product IDs
    },

    init() {
        this.fetchPromotions();
        this.fetchProducts();

        // Watch for filter changes to re-init icons
        this.$watch("currentFilter", () => {
            this.$nextTick(() => {
                if (window.createIcons && window.icons) {
                    window.createIcons({ icons: window.icons });
                }
            });
        });
    },

    async fetchPromotions() {
        try {
            const response = await axios.get("/promotions");
            this.promotions = response.data;
            this.refreshIcons();
        } catch (error) {
            console.error("Error fetching promotions:", error);
            this.showError("Error al cargar las promociones");
        }
    },

    async fetchProducts() {
        try {
            const response = await axios.get("/menu");
            // If /menu returns all products, filter available ones or just use all
            // The existing create view used available products only.
            // Let's assume response.data is the array of products.
            this.availableProducts = response.data.filter(
                (p) => p.is_available
            );
        } catch (error) {
            console.error("Error fetching products:", error);
        }
    },

    get filteredPromotions() {
        if (this.currentFilter === "all") return this.promotions;

        return this.promotions.filter((p) => {
            const isActive = !!p.status || p.status === "1" || p.status === 1;

            if (this.currentFilter === "active") {
                return isActive;
            }
            if (this.currentFilter === "inactive") {
                return !isActive;
            }
            return true;
        });
    },

    refreshIcons() {
        this.$nextTick(() => {
            if (window.createIcons && window.icons) {
                window.createIcons({ icons: window.icons });
            }
        });
    },

    openCreateModal() {
        this.resetForm();
        this.isEdit = false;
        this.openModal = true;
    },

    editPromotion(promotion) {
        this.form = {
            id: promotion.id,
            title: promotion.title,
            description: promotion.description,
            discount_percent: promotion.discount_percent,
            start_date: promotion.start_date
                ? promotion.start_date.split("T")[0]
                : "",
            end_date: promotion.end_date
                ? promotion.end_date.split("T")[0]
                : "",
            badge_label: promotion.badge_label || "",
            status: !!promotion.status,
            image: null,
            image_url: promotion.image_url, // Assuming controller returns this attribute
            image_preview: null,
            products: promotion.products
                ? promotion.products.map((p) => p.id)
                : [],
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
            title: "",
            description: "",
            discount_percent: "",
            start_date: "",
            end_date: "",
            badge_label: "",
            status: true,
            image: null,
            image_url: null,
            image_preview: null,
            products: [],
        };
        const fileInput = document.getElementById("promotion-image");
        if (fileInput) fileInput.value = "";
    },

    handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        this.form.image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            this.form.image_preview = e.target.result;
        };
        reader.readAsDataURL(file);
    },

    addProductToForm() {
        // This is handled by the multi-select or checkbox list in the view
        // Just empty method placeholder if needed, but x-model handles it.
    },

    async savePromotion() {
        const formData = new FormData();
        formData.append("title", this.form.title);
        formData.append("description", this.form.description);
        formData.append("discount_percent", this.form.discount_percent || "");
        formData.append("start_date", this.form.start_date || "");
        formData.append("end_date", this.form.end_date || "");
        formData.append("badge_label", this.form.badge_label || "");
        formData.append("status", this.form.status ? "1" : "0"); // Checkbox usually sends 'on' or nothing, but API expects 1/0 or boolean? Controller validation: $data['status'] = $request->has('status'); wait.
        // Controller Logic: $data['status'] = $request->has('status');
        // This means if I send 'status' field at all, it's true? No, has('status') checks if key exists.
        // If I append 'status' to formData, it exists.
        // PROMBLEM: Axios sending FormData with 'status': '0' might still be considered "has('status')" being true?
        // No, $request->has('status') checks if the input is present.
        // In standard HTML form, unchecked checkbox is NOT sent.
        // In FormData, if I append it, it IS sent.
        // If I want status=false, I should NOT append 'status' to FormData?
        // OR better, update Controller to handle boolean correctly.
        // Controller: $data['status'] = $request->has('status');
        // If I send formData.append('status', '1') -> has('status') is true -> status=true.
        // If I send formData.append('status', '0') -> has('status') is true -> status=true. INCORRECT.
        // FIX: I should ONLY append 'status' if it is true.

        if (this.form.status) {
            formData.append("status", "1");
        }

        if (this.form.image) {
            formData.append("image", this.form.image);
        }

        // Handle products array
        this.form.products.forEach((id) => {
            formData.append("products[]", id);
        });

        if (this.isEdit) {
            formData.append("_method", "PUT");
        }

        try {
            const url = this.isEdit
                ? `/promotions/${this.form.id}`
                : "/promotions";

            await axios.post(url, formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });

            await this.fetchPromotions();
            this.closeModal();
            this.showSuccess(
                this.isEdit ? "Promoción actualizada" : "Promoción creada"
            );
        } catch (error) {
            console.error(error);
            const message = error.response?.data?.errors
                ? Object.values(error.response.data.errors).flat().join(", ")
                : "Error al guardar la promoción";
            this.showError(message);
        }
    },

    async deletePromotion(id) {
        const result = await Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esta acción",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`/promotions/${id}`);
                await this.fetchPromotions();
                this.showSuccess("Promoción eliminada");
            } catch (error) {
                this.showError("Error al eliminar");
            }
        }
    },

    getProduct(id) {
        return this.availableProducts.find((p) => p.id === id);
    },

    formatDate(dateStr) {
        if (!dateStr) return "N/A";
        const date = new Date(dateStr);
        return date.toLocaleDateString("es-ES", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    },

    showSuccess(message) {
        Toast.fire({ icon: "success", title: message });
    },

    showError(message) {
        Toast.fire({ icon: "error", title: message });
    },
});
