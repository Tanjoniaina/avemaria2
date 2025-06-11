import { application } from '@symfony/stimulus-bridge';
import { registerControllersFromDefinitions } from '@symfony/stimulus-bridge';
import OrderController from './order_controller';

application.register('order', OrderController);
registerControllersFromDefinitions(application);
