<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md">
      <form class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4" @submit.prevent="handleSubmit">
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
          <label class="block text-gray-700 font-bold mb-2" for="price">
            Service Price(USD)
          </label>
          <input
            class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            id="price"
            type="number"
            placeholder="Enter service price"
            v-model.number="price"
            required
          >
        </div>
        <div class="my-4">
          <label for="country" class="block text-gray-700 font-bold mb-2">Country</label>
          <input type="text" id="country" v-model="country" required
         @input="search" class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter your country">
          <ul v-if="results.length" class="mt-2 px-2 py-1 bg-white border border-gray-300 rounded-md shadow-md">
            <li v-for="result in results" :key="result.id"  @click="selectCountry(result)" class="cursor-pointer hover:bg-gray-100">{{ result.name }}</li>
          </ul>
        </div>
        <div class="mb-4">
          <label for="city" class="block text-gray-700 font-bold mb-2">City:</label>
          <input 
            class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter your city"
            v-model="city"  
            :disabled="isCityDisabled"
            required
          >
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="category">
            Category
          </label>
          <select class="block w-full py-2 px-3  border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
          id="category" name="category" required
          v-model="selectedCategoryId" >
            <option disabled value="">Select a category</option>
            <option v-for="(category, index) in categories" :key="index" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div class="flex items-center justify-between">
          <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit"
            @click.prevent="submitForm" :disabled="!formIsValid"
          >
           Add Service
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';

export default {
  data() {
    return {
      title: '',
      description: '',
      price: null,
      country: '',
      SelectedCountry: '',
      city: "",
      results: [],
      categories: [],
      selectedCategoryId: '',
      isLoading: false,
      countryselected: false,
    };
  },
  mounted()
  {
    this.fetchCategories();
  },
  computed: {
    formIsValid() {
      return this.title && this.description && this.price && this.country && this.selectedCategoryId;
    },
      isCityDisabled() {
        return !this.countryselected;
      },
      slug() {
        return this.title.toLowerCase().replace(/\s+/g, '-');
      
    },
    },
  methods: {
    fetchCategories(){
      axios.get('/GetCategories')
      .then(response => {
          this.categories = response.data;
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
      if(this.country.length > 2){
        const response = await axios.get('/countries', {
          params: {
            q: this.country,
          },
        });
        this.results = response.data;
      }else {
        this.results = [];
      }
    },
    selectCountry(country) {
        this.SelectedCountry=country;
        this.country = country.name;
        this.countryselected = true;
        this.results = [];
      },
      submitForm() {
      
    const formData = new FormData();
    formData.append('title', this.slug);
    formData.append('description', this.description);
    formData.append('price', this.price);
    formData.append('category_id', this.selectedCategoryId);
    formData.append('country_id',this.SelectedCountry.id);
    formData.append('city', this.city.toLowerCase().replace(/\s+/g, '-'));
    
    
    axios.post('/storeService', formData)
      .then(response => {
        // handle success response
        Swal.fire({
          icon: 'success',
          title: "Success",
          text: response.data.success,
        })
        
      })
      .catch(error => {
        // handle error response
        Swal.fire({
          icon: 'error',
          title: "Error",
          text: error.response.data.error,
        })
        
        
      });
  },
  }}
  </script>