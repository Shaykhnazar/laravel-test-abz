<template>
  <div class="user-form card">
    <div class="card-header">
      <h3>Create User</h3>
    </div>
    <div class="card-body">
      <form @submit.prevent="createUser">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" v-model="form.name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" v-model="form.email" class="form-control" id="email" required>
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Phone</label>
          <input type="text" v-model="form.phone" class="form-control" id="phone" required>
        </div>
        <div class="mb-3">
          <label for="position_id" class="form-label">Position</label>
          <select v-model="form.position_id" class="form-select" id="position_id" required>
            <option v-for="position in positions" :value="position.id" :key="position.id">
              {{ position.name }}
            </option>
          </select>
        </div>
        <div class="mb-3">
          <label for="photo" class="form-label">Photo</label>
          <input type="file" @change="handleFileUpload" class="form-control" id="photo" required>
        </div>
        <button type="submit" class="btn btn-success" :disabled="loading">Create User</button>
      </form>
      <button @click="generateToken" class="btn btn-primary mt-3" :disabled="generatingToken || loading">Generate Token</button>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';
import { notify } from '@kyvg/vue3-notification' // Assuming your api.js is in the services folder

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        position_id: null,
        photo: null,
      },
      positions: [],
      loading: false,
      generatingToken: false,
    };
  },
  methods: {
    async fetchPositions() {
      try {
        const response = await api.positions.getPositions();
        this.positions = response.data.positions;
      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
    handleFileUpload(event) {
      this.form.photo = event.target.files[0];
    },
    async generateToken() {
      this.generatingToken = true; // Disable the button during token generation

      try {
        const response = await api.auth.token();
        const token = response.data.token;

        // Save the token to local storage
        localStorage.setItem('userToken', token);
        notify({
          group: 'messages',
          type: 'success',
          text: 'Token generated and saved successfully!',
          duration: 10000,
        })
      } catch (error) {
        console.error('Error generating token:', error);
      } finally {
        this.generatingToken = false; // Re-enable the button after token generation
      }
    },
    async createUser() {
      const formData = new FormData();
      Object.keys(this.form).forEach((key) => {
        formData.append(key, this.form[key]);
      });

      // Get the token from local storage
      const token = localStorage.getItem('userToken');

      // console.log(token);
      if (!token) {
        notify({
          group: 'messages',
          type: 'warning',
          text: 'Please generate a token first.',
          duration: 5000,
        })
        return;
      }
      this.loading = true; // Disable the button during user creation

      try {
        const response = await api.users.createUser(formData, {
          Token: `Bearer ${token}`,
          'Content-Type': 'multipart/form-data',
        });
        notify({
          group: 'messages',
          type: 'success',
          text: response.data.message,
          duration: 10000,
        })

        // Remove the token from local storage after successful registration
        localStorage.removeItem('userToken');

        this.$router.push('/users'); // Navigate to user list after successful creation
      } catch (error) {
        console.error('Error creating user:', error);
      } finally {
        this.loading = false; // Re-enable the button after user creation
      }
    },
  },
  mounted() {
    this.fetchPositions();
  },
};
</script>

<style scoped>
.user-form {
  max-width: 600px;
  margin: auto;
}
</style>
