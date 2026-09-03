import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './fontawesome/css/all.min.css';
import './fontawesome/css/brands.min.css';
import './fontawesome/css/chisel-regular.min.css';
import './fontawesome/css/duotone-light.min.css';
import './fontawesome/css/duotone-regular.min.css';
import './fontawesome/css/duotone-thin.min.css';
import './fontawesome/css/duotone.min.css';
import './fontawesome/css/etch-solid.min.css';
import './fontawesome/css/fontawesome.min.css';
import './fontawesome/css/graphite-thin.min.css';
import './fontawesome/css/jelly-duo-regular.min.css';
import './fontawesome/css/jelly-fill-regular.min.css';
import './fontawesome/css/jelly-regular.min.css';
import './fontawesome/css/light.min.css';
import './fontawesome/css/notdog-duo-solid.min.css';
import './fontawesome/css/notdog-solid.min.css';
import './fontawesome/css/regular.min.css';
import './fontawesome/css/sharp-duotone-light.min.css';
import './fontawesome/css/sharp-duotone-regular.min.css';
import './fontawesome/css/sharp-duotone-solid.min.css';
import './fontawesome/css/sharp-duotone-thin.min.css';
import './fontawesome/css/sharp-light.min.css';
import './fontawesome/css/sharp-regular.min.css';
import './fontawesome/css/sharp-solid.min.css';
import './fontawesome/css/sharp-thin.min.css';
import './fontawesome/css/slab-press-regular.min.css';
import './fontawesome/css/slab-regular.min.css';
import './fontawesome/css/solid.min.css';
import './fontawesome/css/svg-with-js.min.css';
import './fontawesome/css/svg.min.css';
import './fontawesome/css/thin.min.css';
import './fontawesome/css/thumbprint-light.min.css';
import './fontawesome/css/utility-duo-semibold.min.css';
import './fontawesome/css/utility-fill-semibold.min.css';
import './fontawesome/css/utility-semibold.min.css';
import './fontawesome/css/whiteboard-semibold.min.css';

import Logo from './logo.js';
import Toolbox from './toolbox.js';
import Home from './home.js';

Toolbox.setTheme(localStorage.getItem('theme') || 'corporate');
document.addEventListener('turbo:load', () => {
    Logo();
    Toolbox.init();
});
