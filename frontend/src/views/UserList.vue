<template>
  <div class="user-list">
    <h2>Users</h2>
    <div v-if="loading" class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div v-else>
      <div class="d-flex align-items-center mb-3">
        <label for="count" class="me-2">Number of Users per Page:</label>
        <input
          type="number"
          id="count"
          v-model.number="countPerPage"
          @change="handleCountChange"
          class="form-control w-auto"
          min="1"
        />
      </div>
      <ul class="list-group">
        <li v-for="user in users" :key="user.id" class="list-group-item">
          <router-link :to="`/users/${user.id}`">{{ user.name }}</router-link>
        </li>
      </ul>
      <div class="pagination mt-3" v-if="nextPageUrl">
        <button @click="loadMore" class="btn btn-primary">Show More</button>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api'; // Assuming your api.js is in the services folder

export default {
  data() {
    return {
      users: [],
      loading: true,
      nextPageUrl: null,
      currentPage: 1,
      countPerPage: 6,
    };
  },
  methods: {
    async fetchUsers(page = this.currentPage, count = this.countPerPage) {
      this.loading = true;
      try {
        const response = await api.users.getUsers({ page, count });
        this.users = response.data.users;
        this.nextPageUrl = response.data.links.next_url;
        this.currentPage = response.data.page;
      } catch (error) {
        console.error('Error fetching users:', error);
      } finally {
        this.loading = false;
      }
    },
    handleCountChange() {
      // Reset to first page and fetch users with the new count value
      this.users = [];
      this.currentPage = 1;
      this.fetchUsers(this.currentPage, this.countPerPage);
    },
    async loadMore() {
      if (this.nextPageUrl) {
        const nextPageParams = new URLSearchParams(this.nextPageUrl.split('?')[1]);
        const page = parseInt(nextPageParams.get('page'), 10);
        const count = parseInt(nextPageParams.get('count'), 10) || this.countPerPage;

        try {
          const response = await api.users.getUsers({ page, count });
          this.users = [...this.users, ...response.data.users];
          this.nextPageUrl = response.data.links.next_url;
          this.currentPage = response.data.page;
        } catch (error) {
          console.error('Error loading more users:', error);
        }
      }
    },
  },
  mounted() {
    this.fetchUsers(this.currentPage, this.countPerPage);
  },
};
</script>

<style scoped>
.user-list {
  max-width: 600px;
  margin: auto;
}

.pagination {
  text-align: center;
}
</style>
