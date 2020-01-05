import '../css/app.css';

import $ from 'jquery';
window.jQuery = $;
window.$ = $;

import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/themes/base/all.css';

import 'bootstrap/dist/js/bootstrap.min.js';
import 'bootstrap/dist/css/bootstrap.min.css';

import { library, dom } from '@fortawesome/fontawesome-svg-core';
import {
    faPencilAlt,
    faTrashAlt,
    faSort,
    faPlus,
    faPlusSquare,
    faCalendarAlt,
    faWindowClose
} from '@fortawesome/free-solid-svg-icons';

library.add(
    faPencilAlt,
    faTrashAlt,
    faSort,
    faPlus,
    faPlusSquare,
    faCalendarAlt,
    faWindowClose
);
dom.watch();
