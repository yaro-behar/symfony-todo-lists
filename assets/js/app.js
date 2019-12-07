import '../css/app.css';

import $ from 'jquery';
window.jQuery = $;
window.$ = $;

import 'bootstrap/dist/css/bootstrap.min.css';

import { library, dom } from '@fortawesome/fontawesome-svg-core';
import {
    faPencilAlt,
    faTrashAlt,
    faSort,
    faPlus,
    faCalendarAlt
} from '@fortawesome/free-solid-svg-icons';

library.add(faPencilAlt, faTrashAlt, faSort, faPlus, faCalendarAlt);
dom.watch();
