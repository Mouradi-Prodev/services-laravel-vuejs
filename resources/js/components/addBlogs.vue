<template>
     <!-- Form to add a new blog -->
     <div class="w-full ">
  <div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <form class="px-4 py-2 bg-white rounded-lg shadow-md flex flex-col">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Add a Blog</h3>
      <p class="text-sm text-gray-600">Note: you must design your own blog or a static website in general. and upload the zip file. note 
        that the zip file will be reviewed before publishing. Services and Blogs does not guarantee submission and therefore reserve the right to publish or reject any templates that are submitted.
      </p>

      <div class="flex-row mb-4">
        <label class="block text-gray-700 font-bold mb-2 w-1/3" for="title">Title</label>
        <p class="text-sm text-gray-600">Note:Use appropriate keywords in the title
      </p>
        <input type="text" id="title" 
        class="form-input rounded-lg w-2/3 py-2 px-3 border border-gray-300" v-model="newBlog.title">
      </div>

      <div class="flex-col mb-4">
        <label class="block text-gray-700 font-bold mb-2 w-1/3" for="description">Description</label>
        <textarea id="description" 
        class="form-textarea rounded-lg w-2/3 py-2 px-3 border border-gray-300" rows="3" v-model="newBlog.description"></textarea>
      </div>

      <div class="flex-row mb-4">
        <label class="block text-gray-700 font-bold mb-2 w-1/3" for="zipFile">Zip File</label>
        <input type="file" accept="application/zip" id="zipFile" class="form-input rounded-lg w-2/3 py-2 px-3 border border-gray-300" @change="handleFileUpload">
        <p class="text-sm text-gray-600">Note: The zip file must contain an index.html file and you will be responsible for designing your blog.</p>
    </div>

      <div class="flex justify-end">
        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addBlog">Add Blog</button>
      </div>
    </form>
  </div>
</div>

   
    
  </template>
  <script>
  import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2.js';

  export default {
    data() {
      return {
       
        newBlog: {
          title: '',
          description: '',
          file: null
        }
      }
    },
    methods: {
      addBlog() {
        // get the user input from the form
      const blogTitle = this.newBlog.title.toLowerCase().replace(/\s+/g, '-');
      const blogDescription = this.newBlog.description;
      const blogZipFile = this.newBlog.file;
      
      // create a new FormData object to send the data to the server
      const formData = new FormData();
      formData.append('title', blogTitle);
      formData.append('description',blogDescription);
      formData.append('zipFile', blogZipFile);
      
      // send a POST request to the server to add the new blog post
      axios.post('/api/add-blog-posts', formData)
        .then(response => {
          // add the new blog post to the list of blog posts   
          // reset the form inputs
          this.newBlog.title = '';
          this.newBlog.file = null;
          Swal.fire({
            icon: 'success',
            text: response.data.message
          })
        })
        .catch(error => {
          // handle the error
          Swal.fire({
            icon: 'error',
            title: "Error",
            text: error.response.data.error
          })
          
        });
    },
    handleFileUpload(event) {
      this.newBlog.file =  event.target.files[0];
    }
},
  };
</script>