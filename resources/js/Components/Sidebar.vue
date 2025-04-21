<template>
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-white mb-4">Dashboard</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="/dashboard" class="nav-link active"><i class="bi bi-house-door me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="/form/create" class="nav-link"><i class="bi bi-clipboard me-2"></i> Input JSON</a>
            </li>
            <li class="nav-item mb-2">
                <a href="/form" class="nav-link"><i class="bi bi-clipboard me-2"></i> All Forms</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link"><i class="bi bi-gear me-2"></i> Settings</a>
            </li>
            <li class="nav-item mb-2">
                <form @submit.prevent="logout" class="d-flex align-items-center nav-link" style="cursor: pointer;">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <button type="submit" class="btn btn-link p-0 m-0 text-start text-decoration-none text-danger">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</template>

<script lang="ts" setup>

import {router} from "@inertiajs/vue3";
const logout = () => {
    router.post('/logout');
}
</script>

<style scoped>
.sidebar {
    min-height: 100vh;
    background-color: #0d1b2a;
    color: #ffffff;
}

.sidebar a {
    color: #d6d6d6;
    text-decoration: none;
}

.sidebar a:hover {
    background-color: #1b263b;
    color: #ffffff;
}

.sidebar .active {
    background-color: #1b263b;
    color: #ffffff;
}
</style>
