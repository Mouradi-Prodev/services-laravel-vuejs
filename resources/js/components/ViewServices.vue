
<template>
  <div class="services-container">
    <div class="flex flex-wrap">
      <div v-if="services.length == 0" class="text-center text-gray-500">
        No services available
      </div>
      <div class="grid grid-cols-2 p-4 sm:p-6 lg:p-8">

        <a v-for="service in services" :key="service.id" @click.prevent="navigateToService(service.id)"
          class="max-w-md rounded overflow-hidden shadow-lg mx-4 my-4 hover:scale-105">
          <img class="w-full h-48 object-cover" :src="service.file" alt="Service image">
          <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ service.title }}</div>
            <p class="text-gray-700 text-base">{{ service.description }}</p>
          </div>
          <div class="px-6 pt-4 pb-2">
            <span
              class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 truncate">{{ service.catname }}</span>
            <span
              class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 truncate">{{ service.countname }}</span>
            <span
              class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 truncate">{{ service.cname }}</span>
          </div>
        </a>

      </div>

    </div>
  </div>
</template>


<script>
import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2';
export default {

  data() {
    return {
      services: [],
      serviceData: null,
    }
  },
  mounted() {
    this.fetchservices();
  },
  methods: {
    navigateToService(serviceId) {
      this.$router.push({ name: 'ServiceView', params: { id: serviceId } });
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
                title: 'Error',
                text: error.response.error
              })
              //console.log(error.response.data);
            });

        }
      })

    },
    fetchservices() {
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
      this.serviceData = service;
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

          // Send an Axios request to update the service on the server
          return axios.put(`/editservice/${this.serviceData.id}`, { title, description })
            .then(response => {
              // Reload the page to show the updated service list
              this.fetchservices();
              Swal.fire({
                icon: "success",
                text: "Updated successfully"
              })
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
<style>
.services-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}
</style>