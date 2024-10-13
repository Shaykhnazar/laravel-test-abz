<script setup>
import { RouterView, RouterLink } from 'vue-router';
</script>

<template>
  <div id="app" style="height: 100vh">
    <notifications group="messages" />
    <!-- Navigation -->
    <el-container>
      <nav>
        <ul>
          <li><RouterLink to="/">Home</RouterLink></li>
          <li><RouterLink to="/users">Users</RouterLink></li>
          <li><RouterLink to="/users/new">Create User</RouterLink></li>
          <li><RouterLink to="/positions">Positions</RouterLink></li>
        </ul>
      </nav>
    </el-container>

    <!-- Router View to display components based on the route -->
    <RouterView />
  </div>
</template>

<style scoped>
nav {
  margin-bottom: 20px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline;
  margin-right: 10px;
}
</style>
