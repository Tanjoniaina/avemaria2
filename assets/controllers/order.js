import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    console.log('OrderController connecté');

    // Autocomplete:change listener
    document.querySelectorAll('[data-controller~="symfony--ux-autocomplete--autocomplete"]').forEach(input => {
      input.addEventListener('autocomplete:change', function (event) {
        const selectedOption = event.detail.item;
        const prix = selectedOption?.data?.prix;

        if (prix !== undefined) {
          const inputId = input.getAttribute('id');
          const match = inputId.match(/\d+/);
          if (match) {
            const index = match[0];
            const prixField = document.querySelector(`#purchaseorder_form_ligne_${index}_unitprice`);
            if (prixField) {
              prixField.value = prix;
            }
          }
        }
      });
    });

    // Ajout dynamique des lignes
    const addButton = document.getElementById('add-facture');
    const collection = document.getElementById('purchaseorder_form_ligne');
    const counter = document.getElementById('widget-counter');

    if (addButton && collection && counter) {
      addButton.addEventListener('click', () => {
        const index = parseInt(counter.value);
        const prototype = collection.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);

        const temp = document.createElement('div');
        temp.innerHTML = newForm;
        const formElement = temp.firstElementChild;

        collection.appendChild(formElement);
        counter.value = index + 1;

        this.handleDeleteButtons();
        this.refreshStimulus(); // important
      });
    }

    this.handleDeleteButtons();
  }

  handleDeleteButtons() {
    document.querySelectorAll('button[data-action="delete"]').forEach(button => {
      button.onclick = () => {
        const target = document.querySelector(button.dataset.target);
        target?.remove();
      };
    });
  }

  refreshStimulus() {
    import('@hotwired/stimulus').then(({ Application }) => {
      const app = Application.start();
      app.enhance(); // pour Stimulus v3
    });
  }
}
