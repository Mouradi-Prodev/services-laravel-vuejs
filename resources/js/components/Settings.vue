<template>
  <h2 class="text-2xl font-medium mb-4 text-amber-900">Welcome back, {{username}} </h2>
<div class="flex flex-col md:flex-row justify-center items-start max-w-4xl mx-auto space-y-8 md:space-y-0">
  <!-- Profile section -->
  <div class="w-full md:w-1/2 space-y-6">
    <h2 class="text-3xl font-bold text-pink-900">Profile Settings</h2>
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="flex items-center space-x-4">
        <img class="h-20 w-20 rounded-full object-cover" :src="imageUrl" alt="Profile Image"/>
        
        <div class="flex flex-col">
          <label class="cursor-pointer font-medium text-blue-600 hover:text-blue-800">
            Upload Image
            <input class="sr-only" type="file" id="image" name="image" accept="image/*" @change="handleImageUpload">
          </label>
          <p class="text-sm text-gray-500">PNG, JPG</p>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
       
        <div>
          <label class="block font-medium mb-2" for="firstName">UserName</label>
          <input class="border border-gray-400 p-2 w-full rounded-md" 
          type="text" id="firstName" name="firstName" 
          
          v-model="UserName">
        </div>
        
        <div>
          <label class="block font-medium mb-2" for="email">Email</label>
          <h3 class="rounded-md  text-blue-900 w-full py-2 px-4" id="email" name="email">{{ email }}</h3>
        </div>
      </div>
      <div class="mt-6">
        <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md"
        :disabled="usernamefill"
        @click="UpdateUsername"
        >Save</button>
      </div>
    </div>
  </div>
  <!-- Password change section -->
  <div class="w-full md:w-1/2 space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-3xl text-amber-500 font-bold mb-4">Change Password</h2>
      <form class="space-y-4">
        <div class="mb-4">
          <label class="block font-medium mb-2" for="current_password">Current Password</label>
          <input class="border border-gray-400 p-2 w-full rounded-md" 
          v-model="oldPassword"
          type="password" id="current_password" name="current_password" placeholder="Enter your current password" required>
        </div>
        <div class="mb-4">
          <label class="block font-medium mb-2" for="new_password">New Password</label>
          <input class="border border-gray-400 p-2 w-full rounded-md" 
          v-model="newPassword"
          type="password" id="new_password" name="new_password" placeholder="Enter your new password" required>
        </div>
        <div class="mb-4">
          <label class="block font-medium mb-2" for="confirm_password">Confirm Password</label>
          <input class="border border-gray-400 p-2 w-full rounded-md" 
          v-model="confirmPassword"
          type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required>
        </div>
        <div>
            <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md" 
            @click.prevent="changePassword"
            type="submit">Save Changes</button>
        </div>
      </form>
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
      UserName: '',
      username: '',
      email: '',
      imageUrl: '',
      oldPassword: '',
      newPassword: '',
      confirmPassword: '',
    };
  },
  
  mounted() {
    this.fetchdata();
  },
  computed:{
    usernamefill()
    {
      return !this.UserName;
    }
  },
  methods: {
    changePassword() {
      axios.post('/change-password', {
        old_password: this.oldPassword,
        new_password: this.newPassword,
        new_password_confirmation: this.confirmPassword,
      }).then(response => {
        Swal.fire({
          icon: "success",
          title: response.data.message,
        })
        // Handle success
      }).catch(error => {
        Swal.fire({
          icon: "error",
          title: error.response.data.message,
        })
       
        // Handle errorw
      });
    },
    UpdateUsername()
    {
      axios.post("/UpdateUsername",{username: this.UserName})
      .then(response => {
        Swal.fire({
        icon: "success",
        title: 'Username updated',
        text: 'New Username: '+ response.data.username
      })
      this.fetchdata();
      })
      .catch(error => {
        Swal.fire({
          icon: "error",
          text: "Failed",
        })
      })
      
    },
    fetchdata()
    {
        fetch('/getprofile')
        .then(response => response.json())
        .then(data => {
        this.imageUrl = data.imageUrl;
        this.email = data.email;
        this.username = data.username;
        })
        .catch(error => {
          Swal.fire({
          icon: "error",
          text: "Failed",
        })
        });
    },
    handleImageUpload(event) {
      const file = event.target.files[0];
      // TODO: Upload the file to the server and update the imageUrl
      const formData = new FormData();
        formData.append('image', file);

        axios.post('/uploadImage', formData, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            this.imageUrl = response.data.imageUrl;
        })
        .catch(error => {
            console.log(error);
        });
    },
  },
};
</script>
<style>


</style>