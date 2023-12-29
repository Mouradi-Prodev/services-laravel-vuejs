<template>
  <div class="container mx-auto ">
    <div class="flex flex-col justify-start items-center  bg-white py-6 sm:py-12">
  <div class="flex items-center justify-center mb-4">
    <label for="search" class="mr-2 font-medium text-base md:text-lg">Search</label>
    <input type="text" id="search" class="py-2 px-3 text-sm md:text-base border border-gray-400 rounded-md">
  </div>

  <div class="flex flex-wrap items-center justify-center mb-4">
    <div class="flex items-center mb-2 md:mb-0 md:mr-6">
      <label for="category" class="mr-2 font-medium text-base md:text-lg">Category</label>
      <select 
        id="category" 
        class="py-2 px-3 text-sm md:text-base border border-gray-400 rounded-md"
        v-model="catId"
        @change="SelectChanged"
      >
        <option value="">All</option>
        <option :value="Cat.id" v-for="(Cat, index) in Categories" :key="index">{{ Cat.name }}</option>
      </select>
    </div>

    <div class="flex items-center">
      <label for="country" class="mr-2 font-medium text-base md:text-lg">Country</label>
      <select 
        id="country" 
        class="py-2 px-3 text-sm md:text-base border border-gray-400 rounded-md"
        v-model="countryId"
        @change="SelectChanged"
        @click="initCity"
      >
        <option value="">All</option>
        <option v-for="(Country, index) in Countries" :value="Country.id" :key="index">{{ Country.name }}</option>
      </select>
    </div>
  </div>

  <div class="flex flex-wrap items-center justify-center mb-4">
    <div class="flex items-center mb-2 md:mb-0 md:mr-6">
      <label for="city" class="mr-2 font-medium text-base md:text-lg">City</label>
      <select 
        id="city" 
        class="py-2 px-3 text-sm md:text-base border border-gray-400 rounded-md"
        v-model="cityId"
        @change="SelectChanged"
      >
        <option value="">All</option>
        <option v-for="(City, index) in filteredCities" :value="City.id" :key="index" :selected="City.id == 1">{{ City.name }}</option>
      </select>
    </div>

   
  </div>
  <div class="flex items-center">
      <button type="button" class="px-4 py-2 font-bold text-white bg-blue-500 rounded-r-lg hover:bg-blue-700">
        Search ({{ countRes}})
      </button>
  </div>
</div>


  
  <div>
    <div class="grid grid-cols-2  p-4 sm:p-6 lg:p-8">
    <!-- Example service cards -->
    <a v-for="(Service, index) in displayedServices" :key="index" @click.prevent="navigateToService(Service.id,Service.title,Service.description)" class="max-w-md rounded overflow-hidden shadow-lg mx-4 my-4 hover:scale-105">
      <img class="h-auto max-w-full object-cover .border" :src="Service.file" :alt="Service.title">
      <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2">{{Service.title}}</div>
        <p class="text-gray-700 text-base">{{ Service.description }}</p>
      </div>
      <div class="px-6 pt-4 pb-2">
        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 truncate" >Category 1</span>
        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 truncate">Country 1</span>
        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 truncate">City 1</span>
      </div>
    </a>
  
  
    
     </div>
     <div v-if="pageCount > 1" class="flex justify-center mt-8">
        <nav class="flex items-center bg-white shadow rounded font-medium mx-4">
          <button :disabled="page == 1" @click="page -= 1" class="px-3 py-2 rounded-l focus:outline-none focus:shadow-outline-blue">
            Previous
          </button>
          <template v-for="p in pageCount" :key="p">
            <button  @click="page = p"
                    class="px-3 py-2 rounded-l hover:text-white hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue"
                    :class="{ 'bg-blue-500 text-white': p === page }">{{ p }}</button>
          </template>
          <button :disabled="page == pageCount" @click="page += 1"
                  class="px-3 py-2 rounded-r hover:text-white hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue">
            Next
          </button>
        </nav>
      </div>
  </div>
  </div>
  </template>
  <script>
  import axios from "axios";
  export default{
    data()
    {
      return {
        id:null,
        Categories:'',
        Countries:'',
        Cities:'',
        allCities:null,
        countRes:'0',
        Services:'',
        AllServices: null,
        catId:null,
        countryId:null,
        cityId:null,
        page: 1,
        pageSize: 9 // Number of services per page
      }
    },
    mounted()
    {
      this.fetchData();
      
      console.log(this.$route.params.catId);
    },
    methods:{
      fetchData(){
        axios.post('/api/fetchDataMain')
        .then(response => {
          this.Categories = response.data.Categories;
          this.Countries = response.data.Countries;
          this.AllServices = response.data.AllServices;
          this.allCities = response.data.Cities;
        })
        .catch(error => {
          console.log(error);
        });
      },
      SelectChanged() {
      
        this.$router.push({ name: 'Services', params: { catId: this.catId } });
       
        const catId = this.catId;
        const cityId = this.cityId;
        const countryId = this.countryId;
        
        let filteredServices = this.AllServices;
  
        if (catId) {
          filteredServices = filteredServices.filter(service => service.category_id == catId);
        }
        if (countryId) {
         
          filteredServices = filteredServices.filter(service => service.country_id == countryId);
        }
        if (cityId) {
          filteredServices = filteredServices.filter(service => service.city_id == cityId);
        }
        this.countRes = filteredServices.length;
       // this.Services = filteredServices;
       
      },
      initCity()
      {
        this.cityId = null;
      },
      navigateToService(id,title,description) {
        // Perform any necessary validation on the ID
        // Navigate to the desired URL using the router or window.location
        
        window.location.href = `/show-service/${id}/${title}/${description}`;
      }
    },
    computed: {
      pageCount() {
        return Math.ceil(this.Services.length / this.pageSize);
      },
      displayedServices() {
        const start = (this.page - 1) * this.pageSize;
        const end = start + this.pageSize;
        return this.Services.slice(start, end);
      },
      filteredCities()
      {
        let filteredCities = null;
        const countryId = this.countryId;
        if(countryId)
        {
          filteredCities = this.allCities;
          filteredCities = filteredCities.filter(City => City.country_id == countryId);
          this.Cities = filteredCities;
          return filteredCities;
        }
        return filteredCities;
      }
    }
  }
  </script>