document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('#addProductModal');

    modal.addEventListener('show.bs.modal', () => {
        fetch('/pharmaciegros/product/nouveau-rapide')
            .then(res => res.text())
            .then(html => {
                document.querySelector('#product-form-container').innerHTML = html;
            });
    });

    // soumission du formulaire dans la modal
    document.addEventListener('submit', function (e) {
        const formContainer = e.target.closest('#product-form-container');
        if (!formContainer) return;

        e.preventDefault();

        const form = e.target;
        const data = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: data
        })
            .then(response => {
                if (!response.ok) throw new Error("Erreur serveur");
                return response.json();
            })
            .then(product => {
                // 1. Ajout dynamique dans l'autocomplete (voir point 2)
                // 2. Fermeture de la modal
                const modalElement = document.getElementById('addProductModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
            })
            .catch(error => {
                alert("Erreur lors de l’ajout du produit.");
                console.error(error);
            });
    });

});