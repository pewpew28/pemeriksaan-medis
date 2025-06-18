import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Swal from 'sweetalert2';

// Buat global agar bisa diakses di mana saja
window.Swal = Swal;