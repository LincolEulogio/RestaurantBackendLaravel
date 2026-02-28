export default () => ({
    activeTab: localStorage.getItem('landing_active_tab') || 'about',
    openModal: false,
    modalType: '',
    isEdit: false,

    testimonials: window.landingData?.testimonials || [],
    gallery: window.landingData?.gallery || [],
    values: window.landingData?.values || [],

    testimonialForm: { id: null, name: '', role: '', rating: 5, text: '', platform: 'Google Reviews', date_literal: 'Reciente', image: null },
    galleryForm: { id: null, title: '', span_type: 'col-span-1 row-span-1', order: 0, image: null },
    valueForm: { id: null, title: '', description: '', icon: 'Heart', order: 0 },

    init() {
        this.$watch('activeTab', (val) => {
            localStorage.setItem('landing_active_tab', val);
        });
        console.log("Landing manager initialized with tab:", this.activeTab);
    },

    openCreateModal(type) {
        this.modalType = type;
        this.isEdit = false;
        this.resetForms();
        this.openModal = true;
    },

    closeModal() {
        this.openModal = false;
        this.resetForms();
    },

    resetForms() {
        this.testimonialForm = { id: null, name: '', role: '', rating: 5, text: '', platform: 'Google Reviews', date_literal: 'Reciente', image: null };
        this.galleryForm = { id: null, title: '', span_type: 'col-span-1 row-span-1', order: 0, image: null };
        this.valueForm = { id: null, title: '', description: '', icon: 'Heart', order: 0 };
    },

    // Handlers para mostrar errores de validación (422)
    handleError(error, defaultMsg) {
        console.error(error);
        let msg = defaultMsg;
        if (error.response?.status === 422) {
            const errors = error.response.data.errors;
            msg = Object.values(errors).flat().join('\n');
        } else if (error.response?.data?.message) {
            msg = error.response.data.message;
        }
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg,
                confirmButtonColor: '#3b82f6'
            });
        } else {
            alert(msg);
        }
    },

    showSuccess(title) {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            return Toast.fire({
                icon: 'success',
                title: title
            });
        }
    },

    // VALUES CRUD
    editValue(val) {
        this.valueForm = { ...val };
        this.isEdit = true;
        this.modalType = 'value';
        this.openModal = true;
    },

    async saveValue() {
        try {
            const { id, ...data } = this.valueForm;
            const url = this.isEdit ? `/landing-values/${id}` : '/landing-values';
            
            if (this.isEdit) {
                await axios.post(url, { ...data, _method: 'PUT' });
            } else {
                await axios.post(url, data);
            }
            
            this.showSuccess('Valor guardado con éxito');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            this.handleError(error, 'Error al guardar valor');
        }
    },

    async deleteValue(id) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir este cambio.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/landing-values/${id}`);
                    this.showSuccess('Valor eliminado');
                    setTimeout(() => location.reload(), 1000);
                } catch (error) {
                    this.handleError(error, 'Error al eliminar');
                }
            }
        } else {
            if (confirm('¿Eliminar este valor?')) {
                // fallback
            }
        }
    },

    // TESTIMONIALS CRUD
    editTestimonial(t) {
        this.testimonialForm = { 
            id: t.id,
            name: t.name,
            role: t.role,
            rating: parseInt(t.rating),
            text: t.text,
            platform: t.platform || 'Google Reviews',
            date_literal: t.date_literal || 'Reciente',
            image: null 
        };
        this.isEdit = true;
        this.modalType = 'testimonial';
        this.openModal = true;
    },

    async saveTestimonial() {
        try {
            const formData = new FormData();
            
            // Campos básicos
            const fields = ['name', 'role', 'rating', 'text', 'platform', 'date_literal'];
            fields.forEach(f => {
                if (this.testimonialForm[f] !== null && this.testimonialForm[f] !== undefined) {
                    formData.append(f, this.testimonialForm[f]);
                }
            });

            // Archivo
            if (this.testimonialForm.image instanceof File) {
                formData.append('image', this.testimonialForm.image);
            }
            
            if (this.isEdit) {
                formData.append('_method', 'PUT');
            }
            
            const url = this.isEdit ? `/landing-testimonials/${this.testimonialForm.id}` : '/landing-testimonials';
            await axios.post(url, formData, { 
                headers: { 'Content-Type': 'multipart/form-data' } 
            });
            
            this.showSuccess('Testimonio guardado con éxito');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            this.handleError(error, 'Error al guardar testimonio');
        }
    },

    async deleteTestimonial(id) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: '¿Eliminar testimonio?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Sí, borrar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/landing-testimonials/${id}`);
                    this.showSuccess('Testimonio eliminado');
                    setTimeout(() => location.reload(), 1000);
                } catch (error) {
                    this.handleError(error, 'Error al eliminar');
                }
            }
        }
    },

    // GALLERY CRUD
    editGallery(item) {
        this.galleryForm = { 
            id: item.id,
            title: item.title,
            span_type: item.span_type,
            order: item.order,
            image: null 
        };
        this.isEdit = true;
        this.modalType = 'gallery';
        this.openModal = true;
    },

    async saveGallery() {
        try {
            const formData = new FormData();
            formData.append('title', this.galleryForm.title || '');
            formData.append('span_type', this.galleryForm.span_type);
            formData.append('order', this.galleryForm.order);
            
            if (this.galleryForm.image instanceof File) {
                formData.append('image', this.galleryForm.image);
            }
            
            if (this.isEdit) {
                formData.append('_method', 'PUT');
            }
            
            const url = this.isEdit ? `/landing-gallery/${this.galleryForm.id}` : '/landing-gallery';
            await axios.post(url, formData, { 
                headers: { 'Content-Type': 'multipart/form-data' } 
            });
            
            this.showSuccess('Imagen guardada con éxito');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            this.handleError(error, 'Error al guardar imagen');
        }
    },

    async deleteGallery(id) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: '¿Eliminar imagen?',
                text: "Se borrará permanentemente de la galería.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/landing-gallery/${id}`);
                    this.showSuccess('Imagen eliminada');
                    setTimeout(() => location.reload(), 1000);
                } catch (error) {
                    this.handleError(error, 'Error al eliminar');
                }
            }
        }
    }
});
