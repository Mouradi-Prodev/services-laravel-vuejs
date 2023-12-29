<template>

  <div class="max-w-3xl mx-auto">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between my-4">
      <div class="w-full lg:w-1/2 lg:mr-4">
        <!-- Service Image -->
       
        <div class="my-4">
          <img v-if="service" class="w-full object-cover rounded-lg shadow-md" :src="service.file" :alt="service.title">
        </div>
      </div>
      <div class="w-full lg:w-1/2">
        <!-- Service Title -->
        <h1 class="text-3xl font-bold my-4">{{service.title}}</h1>
        <!-- Service Description -->
        <div class="my-4">
          <p class="text-gray-700">{{service.description}}</p>
        </div>
        <!-- Country and City -->
        <div class="my-4 flex justify-between">
          <div class="flex items-center">
            <span class="text-gray-700 mr-2">Country:</span>
            <span>{{service.country}}</span>
          </div>
          <div class="flex items-center">
            <span class="text-gray-700 mr-2">City:</span>
            <span>{{service.city}}</span>
          </div>
        </div>
        <!-- Update Form -->
        <form class="my-4" @submit.prevent="updateService">
          <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="title">
              Title
            </label>
            <input class="border rounded-lg py-2 px-3 text-gray-700 w-full" id="title" name="title" v-model="updatedService.title">
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="description">
              Description
            </label>
            <textarea class="border rounded-lg py-2 px-3 text-gray-700 w-full" id="description" name="description" v-model="updatedService.description"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="image">
              Image
            </label>
            <input type="file" class="border rounded-lg py-2 px-3 text-gray-700 w-full" id="image" name="image" @change="onFileChange">
          </div>
          <div class="flex justify-end">
            <button v-if="!updating" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg" type="submit">
              Update Service
            </button>
            <button v-if="updating" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
              Updating...
            </button>
          </div>
        </form>
        <!-- Delete Button -->
        <div class="my-4">
          <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg" @click="deleteService">
            Delete Service
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

  
  
  <script>
import axios from 'axios';
import { useToast } from "vue-toastification";
export default{
  setup() {
      // Get toast interface
      const toast = useToast();

      return { toast }
    },
  data() {
    return {
      updating: false,
      service: {
        id:null,
        title: '',
        file: null,
        country:'',
        city:'',
        description: '',
      },
      updatedService: {
        title: '',
        description: '',
        image: null,
      },
    };
  },

  mounted() {
    this.fetchService();
   
  },

  methods: {
    fetchService() {
      axios.post('/GetServiceView', { params: { id: this.$route.params.id } })
        .then(response => {
         
          this.service.id = response.data.id;
          this.service.title = response.data.title;
          this.service.description = response.data.description;
          this.service.country = response.data.country.name;
          this.service.city = response.data.city.name;
          this.service.file = response.data.file;
          this.updatedService.title = this.service.title;
          this.updatedService.description = this.service.description;
        })
        .catch(error => {
          console.error(error);
        });
    },

    updateService() {
      this.updating = true;
      const formData = new FormData();
      formData.append('title', this.updatedService.title);
      formData.append('description', this.updatedService.description);
      if (this.updatedService.image) {
        formData.append('image', this.updatedService.image);
      }
      axios.post(`/editservice/${this.service.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
        .then(response => {
          this.updating = false;
          this.service.id = response.data.id;
          this.service.title = response.data.title;
          this.service.description = response.data.description;
          this.service.file = response.data.file;
          this.toast.success('Service updated successfully!');
        })
        .catch(error => {
        this.updating = false;
          this.toast.error(error.response.data.message);
        });
    },

    deleteService() {
      axios.delete(`/delservice/${this.service.id}`)
        .then(() => {
          this.$router.push('/ViewServices');
          this.toast.success('Service deleted successfully!');
        })
        .catch(error => {
          console.error(error);
          this.toast.error('Failed to delete service!');
        });
    },

    onFileChange(event) {
      this.updatedService.image = event.target.files[0];
    },
  },
};
</script>

  