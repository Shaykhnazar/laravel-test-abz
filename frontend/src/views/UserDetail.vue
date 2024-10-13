<template>
  <div class="user-detail card" v-if="user">
    <div class="card-header">
      <h3>{{ user.name }}</h3>
    </div>
    <div class="card-body">
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Phone:</strong> {{ user.phone }}</p>
      <p><strong>Position:</strong> {{ user.position }}</p>
      <img v-if="user.photo !== null" :src="user.photo" alt="User Photo" class="img-thumbnail">
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
        console.log("User photo URL:", this.user.photo); // Add this line to check the URL
      } catch (error) {
        console.error("Error fetching user:", error);
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
