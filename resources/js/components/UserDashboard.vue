<!-- UserDashboard.vue -->
<template>
     <div class="flex flex-col lg:flex-row">
  <div class="bg-cyan-700 w-full lg:w-64">
    <div class="px-4 py-2 border-b border-gray-700">
      <h2 class="text-lg font-medium text-white"><router-link :to="{ name: 'home' }" exact>Dashboard</router-link></h2>
    </div>
    <nav class="mt-4">
      <router-link to="/addservice" exact class="block px-4 py-2 text-lg font-medium text-white hover:bg-gray-700">add service</router-link>
      <router-link to="/ViewServices" class="block px-4 py-2 text-lg font-medium text-white hover:bg-gray-700" exact>View your Services</router-link>
     
      
      <router-link to="/Settings" class="block px-4 py-2 text-lg font-medium text-white hover:bg-gray-700">Settings</router-link>
    </nav>
  </div>
  <div class="flex-1 p-4">
    
    <router-view></router-view>
  </div>
  
  
</div>
  </template>
 <script>
 export default {
 props: ['userName'],
 
 }
 
 </script>
 
  