import './bootstrap';
import { createRouter, createWebHistory } from 'vue-router';
//import routes from './routes';
import UserDashboard from './components/UserDashboard.vue';
import Dashboard from './components/Dashboard.vue';
import AddService from './components/AddService.vue';
import ViewServices from './components/ViewServices.vue';
import Settings from './components/Settings.vue';
import addBlogs from './components/addBlogs.vue';
import ViewBlogs from './components/ViewBlogs.vue';
import UBlogView from './components/UBlogView.vue';
import Services from './components/Services.vue';
import ServiceDetails from './components/ServiceDetails.vue';
import ServiceView from './components/ServiceView.vue';
import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";

import { createApp } from 'vue';




const app = createApp({});
const app1 = createApp({});

app.component('user-dash', UserDashboard);
app1.component('services',Services);
app1.component('service-details',ServiceDetails);
//app1.component('client-side',nuxt);
const router = createRouter({
    history: createWebHistory(),
    routes: [
      { path: '/home',name:'home', component: Dashboard },
      { path: '/addservice', component: AddService},
      {path: '/ViewServices', component:ViewServices},
      {path: '/Settings', component:Settings},
      {
        path: '/ServiceView_:id',
        name: 'ServiceView',
        component: ServiceView,
        props: true
      },
     
     // {path: '/UBlogView/:id',name:'blog',component: UBlogView,props: true},
      { path: '/Services', component: Services},
      {path: '/service/:id/:title/:description',component: ServiceDetails},
    ]
  });
  

  app.use(Toast).use(router).mount('#app');
  app1.use(router).mount('#app1');