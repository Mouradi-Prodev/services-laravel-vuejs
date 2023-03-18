
<template>
 
 <div class="flex flex-wrap">
  <div v-if="services.length == 0" class="text-center text-gray-500">
        No services available
      </div>
  <div v-for="service in services" :key="service.id">
    <!-- Card component goes here -->
    
    <div class="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-2">
      <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2">{{service.title}}</div>
        <p class="text-gray-700 text-base" @click="showdes(service.description)"><b>description(view)</b></p>
        <p class="text-gray-700 text-base">${{ service.price }}</p>
      </div>
      <div class="px-6 py-4">
        <button @click="editService(service)" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">Edit</button>
      
      
        <button @click="deleteService(service.id)" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-red-700 mr-2">Delete</button>
      </div>
      
    </div>
  </div>
</div>

</template>


<script>
import axios from 'axios';
import  Swal  from 'sweetalert2/dist/sweetalert2';
export default {

  data() {
    return {
      services: [],
      serviceData: null,
    }
  },
  mounted()
  {
    this.fetchservices();
  },
  methods:{
    showdes(des) {
      Swal.fire(
           "description :"+
            des
          )
    },
    deleteService(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/delservice/${id}`)
        .then(response => {
          // handle success
          Swal.fire(
            'Deleted!',
            'Your service has been deleted.',
            'success'
          )
          this.fetchservices();
         
        })
        .catch(error => {
          // handle error
          Swal.fire({
            title : 'Error',
            text : error.response.error
        })
          //console.log(error.response.data);
        });
          
        }
      })
     
    },
    fetchservices()
    {
      axios.get("/GetUserServices")
      .then(response => {
        this.services = response.data;
      })
      .catch(error => {
        Swal.fire({
          icon: "error",
          title: "Error loading services",
        })
      });
    },
    editService(service) {
      this.serviceData= service;
      Swal.fire({
        title: "Edit Service",
        html: `
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="name">
                        Title
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" value="${this.serviceData.title}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description">${this.serviceData.description}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="price">
                        Price
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" type="number" step="0.01" value="${this.serviceData.price}">
                </div>
                <!-- Add other form fields as needed -->
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: "Save",
        cancelButtonText: "Cancel",
        focusConfirm: false,
        preConfirm: async () => {
            // Handle form submission and service update here
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const price = document.getElementById('price').value;

            // Send an Axios request to update the service on the server
            return axios.put(`/editservice/${this.serviceData.id}`, { title, description, price })
              .then(response => {
                // Reload the page to show the updated service list
                this.fetchservices();
              })
              .catch(error => {
                console.error(error);
                Swal.showValidationMessage(`Error: ${error}`);
              });

        }
    });

    },
  }
}
</script>
