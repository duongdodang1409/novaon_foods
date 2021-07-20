import Vue from 'vue';
import Cookies from 'js-cookie';
import ElementUI from 'element-ui';
import App from './views/App';
import store from './store';
import router from './router';
import i18n from './lang'; // Internationalization
import './icons'; // icon
import './permission'; // permission control
import Example from './components/ExampleComponent.vue';
import Menu3 from  './components/Thu3Component';
import Menu4 from  './components/Thu4Component';
import Menu5 from  './components/Thu5Component';
import Menu6 from  './components/Thu6Component';
import Menu7 from  './components/Thu7Component';
import NavMenu  from   './components/MenuComponent';
import Hello from './views/hello';
// Import Ckeditor
import CKEditor from 'ckeditor4-vue';
// Import VueCookies
import VueCookies from 'vue-cookies'

Vue.use(VueCookies)
import * as filters from './filters'; // global filters

import swal from 'sweetalert2'

window.swal = swal;
const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', swal.stopTimer)
    toast.addEventListener('mouseleave', swal.resumeTimer)
  }
})
window.toast = toast;

Vue.use(CKEditor);



Vue.use(ElementUI, {
  size: Cookies.get('size') || 'medium', // set element-ui default size
  i18n: (key, value) => i18n.t(key, value),
});

// register global utility filters.
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

Vue.config.productionTip = false;

new Vue({
  el: '#app',
  router,
  store,
  i18n,
  render: h => h(App),
});
const vue=new Vue({
  el:'#food',
  components:{
    'nav_menu':NavMenu,
    'example':Example,
    'menu3':Menu3,
    'menu4':Menu4,
    'menu5':Menu5,
    'menu6':Menu6,
    'menu7':Menu7,
    'hello':Hello,
    // 'footer':Footer
  }
})
