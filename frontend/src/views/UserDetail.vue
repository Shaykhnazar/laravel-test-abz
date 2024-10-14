<template>
  <div class="user-detail card" v-if="user">
    <div class="card-header">
      <h3>{{ user.name }}</h3>
    </div>
    <div class="card-body">
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Phone:</strong> {{ user.phone }}</p>
      <p><strong>Position:</strong> {{ user.position }}</p>
      <a v-if="user.photo !== null" :href="user.photo" target="_blank">
        <img :src="user.photo" alt="User Photo" class="img-thumbnail" width="70px" height="70px">
      </a>
      <p v-else>No photo available.</p>
    </div>
    <router-link to="/users" class="btn btn-secondary mt-3">Back to Users</router-link>
  </div>
  <div v-else>
    <p>Loading user details...</p>
  </div>
</template>
<script>
import api from '@/services/api';
import { notify } from '@kyvg/vue3-notification'

export default {
  props: ['id'],
  data() {
    return {
      user: null,
      loading: true,
    };
  },
  methods: {
    async fetchUser() {
      try {
        const response = await api.users.getUser(this.id);
        this.user = response.data.user;
        const baseURL = import.meta.env.VITE_ASSETS_URL;
        this.user.photo = `${baseURL}/storage/${this.user.photo}`;
        console.log("User photo URL:", this.user.photo); // Add this line to check the URL
      } catch (error) {
        console.error("Error fetching user:", error);
        notify({
          group: 'messages',
          type: 'error',
          text: error.response.data.message,
          duration: 10000,
        })
      } finally {
        this.loading = false;
      }
    }
  },
  mounted() {
    this.fetchUser();
  }
};
</script>

<style scoped>
.user-detail {
  max-width: 600px;
  margin: auto;
}
</style>
