<template>
    <div class="d-flex">
        <Sidebar />

        <!-- Main Content Area -->
        <div class="flex-grow-1 p-4">
            <slot />
        </div>

    </div>
</template>

<script setup>
import Sidebar from "@/Components/Sidebar.vue";
import { ref } from 'vue';

const selectedField = ref({
    className: '',
    customStyle: '',
});

// Flag to indicate whether we are on the edit page
const isEditPage = ref(true); // Set this to `true` when on the edit page
</script>

<style scoped>
/* Main Layout Styles */
.d-flex {
    display: flex;
}

.sidebar {
    min-height: 100vh;
    width: 250px;
    background-color: #0d1b2a;
    color: #ffffff;
}

.right-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 250px;
    height: 100vh;
    background-color: #0d1b2a;
    padding: 20px;
    color: #ffffff;
    box-shadow: -5px 0 10px rgba(0, 0, 0, 0.1);
}

.right-sidebar label {
    font-size: 14px;
    font-weight: 600;
}

.right-sidebar input,
.right-sidebar textarea {
    margin-top: 10px;
    font-size: 14px;
}
</style>
