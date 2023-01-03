/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue';
/* importa e configura o vuex */
import Vuex from 'vuex'
Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        item: {},
        transacao: {status: '', mensagem: '', dados:''},
        
    }
})


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//aula 343
Vue.component('login-component', require('./components/login.vue').default);


//aula 347
Vue.component('home-component', require('./components/Home.vue').default);


//aula 349
Vue.component('marcas-component', require('./components/Marcas.vue').default);

//aula 351
Vue.component('input-container-component', require('./components/InputContainer.vue').default);

//aula 353
Vue.component('table-component', require('./components/Table.vue').default);

//aula 354
Vue.component('card-component', require('./components/Card.vue').default);

//aula 356
Vue.component('modal-component', require('./components/Modal.vue').default);


//aula 362
Vue.component('alert-component', require('./components/alert.vue').default);

//aula 372
Vue.component('paginate-component', require('./components/Paginate.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//aula 388
Vue.filter('formataDataTempoGlobal', function(d){

    if(!d)  return ''

    d = d.split('T')

    let data = d[0]
    let tempo = d[1]

    //formata a data
    data = data.split('-')
    data = data[2] + '/' + data[1] + '/' + data[0]

    //formata o tempo
    tempo = tempo.split('.')
    tempo = tempo[0]


    return data + ' ' + tempo

})

const app = new Vue({
    el: '#app',
    store
});
