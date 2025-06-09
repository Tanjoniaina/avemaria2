import { Application } from 'stimulus'; // Import de Stimulus
import { startStimulusApp } from '@symfony/stimulus-bridge'; // Initialisation Symfony Bridge
import '@symfony/ux-autocomplete'; // Pour l'autocomplétion

const app = startStimulusApp();