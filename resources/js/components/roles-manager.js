export default () => ({
    openModal: false,
    currentRole: { id: null, name: "", slug: "", permissions: {} },

    resetForm() {
        this.currentRole = { id: null, name: "", slug: "", permissions: {} };
        this.openModal = true;
    },
});

// Role row component for managing individual role permissions
export const roleRow = (roleId, permissions, roleSlug) => ({
    permissions: permissions || {},
    roleSlug: roleSlug || "",
    hasChanges: false,

    init() {
        // If superadmin, enable all permissions and mark as readonly
        if (this.roleSlug === "superadmin") {
            const allPermissions = [
                "dashboard",
                "orders",
                "kitchen",
                "billing",
                "reservations",
                "tables",
                "menu",
                "categories",
                "promotions",
                "blogs",
                "inventory",
                "billing_reports",
                "reports",
                "users",
                "roles",
                "settings",
            ];

            allPermissions.forEach((perm) => {
                this.permissions[perm] = true;
            });
        }
    },

    togglePermission(perm) {
        // Prevent editing superadmin permissions
        if (this.roleSlug === "superadmin") {
            return;
        }

        // Toggle the permission locally
        this.permissions[perm] = !this.permissions[perm];
        this.hasChanges = true;
    },

    savePermissions() {
        fetch(`/roles/${roleId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
            body: JSON.stringify({
                permissions: this.permissions,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    this.hasChanges = false;

                    // Show success toast
                    const Toast = window.Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                window.Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                window.Swal.resumeTimer
                            );
                        },
                    });

                    Toast.fire({
                        icon: "success",
                        title:
                            data.message ||
                            "Permisos actualizados correctamente",
                    });
                } else {
                    window.Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudieron actualizar los permisos.",
                    });
                }
            })
            .catch((err) => {
                console.error(err);
                window.Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error inesperado.",
                });
            });
    },
});
