<template>
    <div class="p-4 sm:p-6 lg:p-8">
      <div class="max-w-md rounded overflow-hidden shadow-lg">
        <img class="w-full h-48 object-cover" :src="`https://source.unsplash.com/random/800x600/?technology`" :alt="this.$route.params.title">
        <div class="px-6 py-4">
          <div class="font-bold text-xl mb-2">{{ this.$route.params.title }}</div>
          <p class="text-gray-700 text-base">description</p>
          <p class="text-gray-700 text-sm mt-2">Published by: Mouradi</p>
        </div>
      </div>
  
      <form class="mt-6">
        <div>
          <label class="block text-gray-700 font-bold mb-2" for="message">Message:</label>
          <textarea class="resize-none shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="message" name="message" rows="6" placeholder="Type your message here..."></textarea>
        </div>
  
        <div class="mt-6">
          <button type="submit" @click.prevent="redirect" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Send Message
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
   
    data()
    {
      return {
        service:null
      }
    },
    mounted()
    {
      //this.fetchService(this.$route.params.id,this.$route.params.title);
    },
    methods: {
      redirect()
      {
        window.location.href="/addBlogs";
      }
    }

  }
  </script>
  