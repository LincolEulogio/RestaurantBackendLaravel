export default () => ({
    openModal: false,
    blogs: [],
    form: {
        id: null,
        title: "",
        slug: "",
        content: "",
        status: "draft",
        image: null,
    },
    isEdit: false,
    currentFilter: "all",

    init() {
        this.fetchBlogs();
    },

    async fetchBlogs() {
        try {
            const { data } = await axios.get("/blogs");
            // Handle both pagination object and direct array
            this.blogs = data.data
                ? data.data
                : Array.isArray(data)
                ? data
                : [];

            // Re-initialize icons after DOM update
            this.$nextTick(() => {
                if (window.createIcons && window.icons) {
                    window.createIcons({ icons: window.icons });
                }
            });
        } catch (error) {
            console.error(error);
            this.blogs = [];
            this.showError("Error al cargar los blogs");
        }
    },

    get filteredBlogs() {
        if (this.currentFilter === "all") {
            return this.blogs;
        }
        return this.blogs.filter((b) => b.status === this.currentFilter);
    },

    setFilter(status) {
        this.currentFilter = status;
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

    editBlog(blog) {
        this.form = {
            id: blog.id,
            title: blog.title,
            slug: blog.slug,
            content: blog.content,
            status: blog.status,
            image: null,
            image_url: blog.image_url,
            image_preview: null,
        };
        this.isEdit = true;
        this.openModal = true;
    },

    closeModal() {
        this.openModal = false;
        setTimeout(() => this.resetForm(), 300); // Wait for transition
    },

    resetForm() {
        this.form = {
            id: null,
            title: "",
            slug: "",
            content: "",
            status: "draft",
            image: null,
            image_url: null,
            image_preview: null,
        };
        const fileInput = document.getElementById("blog-image");
        if (fileInput) fileInput.value = "";
    },

    generateSlug() {
        this.form.slug = this.form.title
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9]+/g, "-")
            .replace(/(^-|-$)+/g, "");
    },

    handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        this.form.image = file;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.form.image_preview = e.target.result;
        };
        reader.readAsDataURL(file);
    },

    async saveBlog() {
        const formData = new FormData();
        formData.append("title", this.form.title);
        formData.append("slug", this.form.slug);
        formData.append("content", this.form.content);
        formData.append("status", this.form.status);

        if (this.form.image) {
            formData.append("image", this.form.image);
        }

        try {
            const url = this.isEdit ? `/blogs/${this.form.id}` : "/blogs";
            const method = "post"; // Always post for FormData

            if (this.isEdit) {
                formData.append("_method", "PUT");
            }

            await axios.post(url, formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });

            await this.fetchBlogs();
            this.closeModal();
            this.showSuccess(
                this.isEdit
                    ? "Blog actualizado exitosamente"
                    : "Blog creado exitosamente"
            );
        } catch (error) {
            const message = error.response?.data?.errors
                ? Object.values(error.response.data.errors).flat().join(", ")
                : "Error al guardar el blog";
            this.showError(message);
        }
    },

    async deleteBlog(id) {
        const result = await Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`/blogs/${id}`);
                await this.fetchBlogs();
                this.showSuccess("Blog eliminado exitosamente");
            } catch (error) {
                this.showError("Error al eliminar el blog");
            }
        }
    },

    formatDate(date) {
        if (!date) return "No publicado";
        return new Date(date).toLocaleDateString("es-ES", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    },

    showSuccess(message) {
        Toast.fire({ icon: "success", title: message });
    },

    showError(message) {
        Toast.fire({ icon: "error", title: message });
    },
});
