import Swal from "sweetalert2";

// Global Configuration
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

// Expose to window for global access if needed
window.Swal = Swal;
window.Toast = Toast;

// Helper to handle Laravel Flash Messages
export function handleFlashMessages() {
    // Check for success message in meta tag or data attribute
    const successMsg = document.querySelector(
        'meta[name="flash-success"]'
    )?.content;
    const errorMsg = document.querySelector(
        'meta[name="flash-error"]'
    )?.content;
    const errorsJson = document.querySelector(
        'meta[name="flash-errors"]'
    )?.content;

    if (successMsg) {
        Toast.fire({
            icon: "success",
            title: successMsg,
        });
    }

    if (errorMsg) {
        Toast.fire({
            icon: "error",
            title: errorMsg,
        });
    }

    if (errorsJson) {
        try {
            const errors = JSON.parse(errorsJson);
            let errorHtml = '<ul style="text-align: left;">';
            Object.values(errors)
                .flat()
                .forEach((err) => {
                    errorHtml += `<li>${err}</li>`;
                });
            errorHtml += "</ul>";

            Swal.fire({
                icon: "error",
                title: "Error de Validación",
                html: errorHtml,
                confirmButtonColor: "#d33",
                confirmButtonText: "Entendido",
            });
        } catch (e) {
            console.error("Error parsing flash errors:", e);
        }
    }
}

// Global Delete Confirmation Handler
export function initDeleteConfirmation() {
    document.addEventListener("submit", function (e) {
        if (e.target.classList.contains("delete-form")) {
            e.preventDefault();
            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esta acción",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        }
    });
}
