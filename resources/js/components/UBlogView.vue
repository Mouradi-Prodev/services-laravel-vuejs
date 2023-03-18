<template>
  <div class="w-full max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Blog</h2>
    <div class="border-b mb-4">Url of your Blog : <a :href="`/blogs/${blog.title}`" target="_blank"> {{ websiteUrl }}</a></div> <!-- Add this line for the border -->
    <form @submit.prevent="handleSubmit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2" for="title">
          Title
        </label>
        <input
          v-model="this.blog.title"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="title"
          type="text"
          placeholder="Enter a title"
        />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2" for="description">
          Description
        </label>
        <textarea
         v-model="this.blog.description"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="description"
          placeholder="Enter a description"
        ></textarea>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2" for="zipFile">
          Blog Zip File
        </label>
        <p class="text-sm text-gray-600">Note: if you upload a new ZipFile your blog will be sent to review. changing only title and description the blog will remain published</p>
        <input
          @change="handleFileUpload"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="zipFile"
          type="file"
          ref="fileInput"
          accept="application/zip"
        />
      </div>
      <div class="flex items-center justify-between">
        <button
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          type="submit"
          
        >
          Save Changes
        </button>
        <button
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="button"
        @click="confirmDelete()"
    >
        Delete Blog
    </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2.js';



export default {
  props: ['id'],
  data() {
    return {
      idblog:this.$route.params.id,
      blog: {
        title: '',
        description: '',
      },
      zipFile: null,
      websiteUrl: '',
    };
  },
  methods: {
     confirmDelete() {
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
        axios.delete('/api/delete-theblog', { data: { id: this.idblog } })
          .then(response => {
            // handle success
            Swal.fire(
              'Deleted!',
              response.data.message,
              'success'
            )
            this.$router.push('/ViewBlogs');
          })
          .catch(error => {
            // handle error
            Swal.fire(
              'Error!',
              'An error occurred while deleting the blog.',
              'error'
            )
          })
      }
    })
   
    },
    handleSubmit() {
      // Submit the form data to the server
      const blogTitle = this.blog.title.toLowerCase().replace(/\s+/g, '-');
     
      
      // create a new FormData object to send the data to the server
      const formData = new FormData();
      formData.append('id',this.idblog);
      formData.append('title', blogTitle);
      formData.append('description',this.blog.description);
      formData.append('zipFile', this.zipFile);
      axios.post('/api/update-blog',formData)
      .then((response) => {
        this.zipFile = null;
        this.$refs.fileInput.value = null;
        Swal.fire({
            icon: 'success',
            text: response.data.message
          })
      })
      .catch((error) => {
        Swal.fire({
            icon: 'error',
            title: "Error",
            text: error.response.data.error
          })
      })
      
      // ...
    },
    handleFileUpload(event) {
      this.zipFile = event.target.files[0];
     // this.blog.zipFile = file;
    },
  },
  mounted() {
    // Fetch the blog with the specified id from the server
    
    axios.get(`/api/blogs/${this.$route.params.id}`).then((response) => {
      
      this.blog = response.data.blog;
      this.websiteUrl = window.location.protocol + "//" + window.location.host + "/blogs/"+this.blog.title;
    });
  },
};
</script>
