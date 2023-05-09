<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md">
      <form class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-4">Add a Service</h2>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="title">
            Service Title
          </label>
          <input
            class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            id="title"
            type="text"
            placeholder="Enter service title"
            v-model="title"
            required
          >
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="description">
            Service Description
          </label>
          <textarea
            class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            id="description"
            placeholder="Enter service description"
            v-model.trim="description"
            required
          ></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="image">
            Service Image
          </label>
          <input
            id="image"
            type="file"
            accept="image/*"
            @change="handleImageUpload"
            
            required
          >
          <img v-if="imageUrl && file" :src="imageUrl" alt="Service Image">
        </div>
        <div class="my-4">
          <label for="country" class="block text-gray-700 font-bold mb-2">Country</label>
          <input type="text" id="country" v-model="country" required
         class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter your country">
          <ul v-if="results.length" class="mt-2 px-2 py-1 bg-white border border-gray-300 rounded-md shadow-md">
            <li v-for="result in results" :key="result.id"  @click="selectCountry(result)" class="cursor-pointer hover:bg-gray-100">{{ result.name }}</li>
          </ul>
        </div>
        <div class="mb-4" v-if="!isCityDisabled">

          <label for="city" class="block text-gray-700 font-bold mb-2">City:</label>
          <select  class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
          id="category" name="category" required
          v-model="selectedCityId" >
            <option  :value="-1">other</option>
            <option v-for="(city, index) in filteredCities" :key="index" :value="city.id">
              {{ city.name }}
            </option>
          </select>
          <div v-if="selectedCityId==-1">
            <label for="city" class="block text-gray-700 font-bold mb-2">Suggest New City:</label>
            <p class="text-sm text-gray-500">if the city is valid it will be available and your service will be shown</p>
          <input 
            class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter your city"
            v-model="city"  
            :disabled="isCityDisabled"
            required
          >
          </div>
         
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="category">
            Category
          </label>
          <select class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
          id="category" name="category" required
          v-model="selectedCategoryId" >
            <option :value="-1">other</option>
            <option v-for="(category, index) in categories" :key="index" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <div v-if="selectedCategoryId==-1">
            <label class="block text-gray-700 font-bold mb-2" for="category">
            Suggest New Category:
          </label>
          <p class="text-sm text-gray-500">if the category is validated by the administrator it will be available and your service will be shown</p>
          <input 
            class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter new category"
            v-model="category"  
           
            required
          >
          </div>
        </div>
        <div class="flex items-center justify-between">

          <button v-if="formIsValid"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline "
            type="submit"
            @click.prevent="submitForm" :disabled="!formIsValid"
          >
          <span v-if="!loading">Add Service</span> <!-- Show "Add Service" when not loading -->
          <span v-else>Loading...</span> <!-- Show "Loading..." when loading -->
          </button>



        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2.min.js';
import 'sweetalert2/src/sweetalert2.scss';

export default{
  data() {
    return {
      title: '',
      description: '',
      
      country: '',
      SelectedCountry: '',
      city: null,
      results: [],
      categories: [],
      category:null,
      selectedCategoryId: null,
      isLoading: false,
      countryselected: false,
      imageUrl:null, 
      file:null,
      selectedCityId:null,
      cities: [],
      loading: false,
      
    };
  },
  mounted()
  {
    this.fetchall();
  },
  watch:{
    country(newCountry)
    {
      if(newCountry.length>1)
      {
        this.search();
      }else{
        this.results = [];
      }
    }

  },
  computed: {
    formIsValid() {
      return this.title && this.description && this.file  && this.country && this.selectedCityId &&  (this.selectedCityId != -1 || (this.selectedCityId==-1  && this.city)) && this.selectedCategoryId && (this.selectedCategoryId!=-1 || (this.selectedCategoryId==-1 && this.category));
    },
      isCityDisabled() {
        return !this.countryselected;
      },
      slug() {
        return this.title.toLowerCase().replace(/\s+/g, '-');
      
    },
    filteredCities() {
      return this.cities.filter(c=> c.country_id == this.SelectedCountry.id);
    }
    },
  methods: {
  
    handleImageUpload(event)
    {
      this.file = event.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(this.file);
      reader.onload = () => {
        this.imageUrl = reader.result;
      };
    },
    fetchall(){
      axios.post('/GetCategories')
      .then(response => {
          this.categories = response.data.categories;
          this.cities = response.data.cities;
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'An error occurred while processing your request',
          })
        });
    },
    async search() {
      // Faire une requête AJAX pour récupérer les pays correspondants
      // et mettre à jour la liste des résultats
      this.countryselected = false;
      try {
        const response = await axios.options('/countries',{params:{q: this.country}})
        this.results = response.data;
      } catch (error) {
        console.log('Error! Could not reach the API. ' + error);
        
      }
      /*if(this.country.length > 2){
        const response = await axios.options('', {
          params: {
            q: this.country,
          },
        });
        this.results = response.data;
      }else {
        this.results = [];
      }*/
    },
    selectCountry(country) {
        this.SelectedCountry=country;
        this.country = country.name;
        this.countryselected = true;
        this.results = [];
      },
      submitForm() {
      if(this.title && this.description && this.file  && this.country && this.selectedCityId &&  (this.selectedCityId != -1 || (this.selectedCityId==-1  && this.city)) && this.selectedCategoryId && (this.selectedCategoryId!=-1 || (this.selectedCategoryId==-1 && this.category)))
      {
        let  city;
        let category;
        if(this.city_id == -1)
        {
           city = this.city.toLowerCase();
        }else{
          city = this.city;
        }
        if(this.category_id == -1)
        {
           category = this.category.toLowerCase();
        }else{
          category = this.category;
        }
        const formData = new FormData();
    formData.append('title', this.slug);
    formData.append('description', this.description);
    formData.append('category_id', this.selectedCategoryId);
    formData.append('country_id',this.SelectedCountry.id);
    formData.append('city', city);
    formData.append('file',this.file);
    formData.append('city_id',this.selectedCityId);
    formData.append('category',category);
    this.loading = true;

    axios.post('/storeService', formData)
      .then(response => {
        // handle success response
        Swal.fire({
          icon: 'success',
          title: "Success",
          text: response.data.success,
        })
        this.loading = false;
      })
      .catch(error => {
        // handle error response
   
        Swal.fire({
          icon: 'error',
          title: "Error",
          text: error.response.data.message,
        })
        this.loading = false;
        
      });
      }else {
        Swal.fire({
          icon: 'error',
          title: "Error, fill all fields",
        })
        
      }
   
  },
  }}
  </script>