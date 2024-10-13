<template>
  <div class="position-list">
    <h2>Positions</h2>
    <div v-if="loading" class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <ul v-else class="list-group">
      <li v-for="position in positions" :key="position.id" class="list-group-item">
        {{ position.name }}
      </li>
    </ul>
  </div>
</template>

<script>
import api from '@/services/api';

export default {
  data() {
    return {
      positions: [],
      loading: true,
    };
  },
  methods: {
    async fetchPositions() {
      try {
        const response = await api.positions.getPositions();
        this.positions = response.data.positions;
      } catch (error) {
        console.error("Error fetching positions:", error);
      } finally {
        this.loading = false;
      }
    }
  },
  mounted() {
    this.fetchPositions();
  }
};
</script>

<style scoped>
.position-list {
  max-width: 600px;
  margin: auto;
}
</style>
