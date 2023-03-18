<template>
    <div class="container mx-auto">
      <div class="flex justify-center">
        <input
          type="text"
          placeholder="Search blogs"
          class="w-full border rounded py-2 px-3"
          v-model="searchText"
        >
      </div>
   
      <div class="">
        <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 p-4" v-for="blog in filteredBlogs" :key="blog.id">
            <router-link :to="{ name: 'blog', params: { id: blog.id } }"> 
                <div class="border rounded p-4 hover:shadow-lg">
            <h2 class="font-bold text-lg mb-2 text-orange-500">{{ blog.title }}</h2>
            <p class="text-indigo-700 truncate">{{ blog.description }}</p><br>
            
            </div>
        </router-link> 
        </div>
        </div>

            </div>
            </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        blogs: [],
        searchText: '',
      };
    },
    computed: {
      filteredBlogs() {
        // Filter blogs based on the search text
        return this.blogs.filter((blog) => {
          const titleMatch = blog.title.toLowerCase().includes(this.searchText.toLowerCase());
          const descriptionMatch = blog.description.toLowerCase().includes(this.searchText.toLowerCase());
          return titleMatch || descriptionMatch;
        });
      },
    },
    mounted() {
      // Fetch all blogs from the server
      axios.get('/api/blogs').then((response) => {
        this.blogs = response.data;
      });
    },
  };
  </script>
  