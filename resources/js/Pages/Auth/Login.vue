<template>
    <AuthLayout>
        <div class="card p-4 shadow-lg bg-dark text-light" style="max-width: 400px; width: 100%; border-radius: 1rem;">
            <h2 class="mb-4 text-center text-light">Login</h2>
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="form-control bg-dark text-light border-secondary"
                        id="email"
                        required
                        placeholder="Email address"
                    />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control bg-dark text-light border-secondary"
                        id="password"
                        required
                        placeholder="Password"
                    />
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>
            </form>
        </div>
    </AuthLayout>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/login');
};
</script>

<style scoped>

::placeholder {
    color: #ccc !important; /* Light gray so it shows on dark bg */
    opacity: 1; /* Make sure it's fully visible */
}
.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

</style>
