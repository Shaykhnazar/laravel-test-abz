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
        <label for="page" class="ms-3 me-2">Page:</label>
        <input
          type="number"
          id="page"
          v-model.number="currentPage"
          @change="handlePageChange"
          class="form-control w-auto"
          min="1"
        />
      </div>
      <ul class="list-group">
        <li v-for="user in users" :key="user.id" class="list-group-item">
          <router-link :to="`/users/${user.id}`">{{ user.name }}</router-link>
        </li>
      </ul>
      <div class="pagination mt-3">
        <button
          v-if="prevPageUrl"
          @click="loadPrevious"
          class="btn btn-secondary me-2"
        >
          Previous
        </button>
        <button v-if="nextPageUrl" @click="loadNext" class="btn btn-primary">
          Next
        </button>
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
      prevPageUrl: null,
      currentPage: 1,
      countPerPage: 5,
    };
  },
  methods: {
    async fetchUsers(page = this.currentPage, count = this.countPerPage) {
      this.loading = true;
      try {
        const response = await api.users.getUsers({ page, count });
        this.users = response.data.users;
        this.nextPageUrl = response.data.links.next_url;
        this.prevPageUrl = response.data.links.prev_url;
        this.currentPage = response.data.page;
      } catch (error) {
        console.error('Error fetching users:', error);
      } finally {
        this.loading = false;
      }
    },
    handleCountChange() {
      this.fetchUsers(1, this.countPerPage);
    },
    handlePageChange() {
      this.fetchUsers(this.currentPage, this.countPerPage);
    },
    async loadNext() {
      if (this.nextPageUrl) {
        const nextPageParams = new URLSearchParams(this.nextPageUrl.split('?')[1]);
        const page = parseInt(nextPageParams.get('page'), 10);
        const count = parseInt(nextPageParams.get('count'), 10) || this.countPerPage;
        await this.fetchUsers(page, count);
      }
    },
    async loadPrevious() {
      if (this.prevPageUrl) {
        const prevPageParams = new URLSearchParams(this.prevPageUrl.split('?')[1]);
        const page = parseInt(prevPageParams.get('page'), 10);
        const count = parseInt(prevPageParams.get('count'), 10) || this.countPerPage;
        await this.fetchUsers(page, count);
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
