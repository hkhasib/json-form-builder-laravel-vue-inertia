<script lang="ts">
import { router } from '@inertiajs/vue3';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

export default {
    props: {
        forms: Object
    },
    components: {
        DashboardLayout
    },
    methods: {
        editForm(formId: number) {
            window.location.href = `/form/${formId}/edit`;
        },
        viewForm(formId: number) {
            window.location.href = `/form/show/${formId}`;
        },
        goToPage(url: string) {
            if (url) {
                router.visit(url, {
                    preserveScroll: true,
                    preserveState: true
                });
            }
        }
    }
};
</script>

<template>
    <DashboardLayout>
        <div class="container mt-5">
            <h2 class="text-center mb-4">Dashboard</h2>
            <p class="text-center mb-4">Welcome to the dashboard!</p>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Action</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="form in forms.data" :key="form.id">
                    <td>{{ form.id }}</td>
                    <td>{{ form.title }}</td>
                    <td>{{ form.action }}</td>
                    <td>{{form.status}}</td>
                    <td>{{ form.method }}</td>
                    <td>
                        <button @click="editForm(form.id)" class="btn btn-primary btn-sm mr-2">
                            Edit
                        </button>
                        <button v-if="form.status=='published'" @click="viewForm(form.id)" class="btn btn-success btn-sm">
                            View
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="pagination text-center mt-4">
                <button
                    v-for="(link, index) in forms.links"
                    :key="index"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    :disabled="!link.url"
                    :class="['btn', 'btn-sm', link.active ? 'btn-dark' : 'btn-outline-secondary', 'mx-1']"
                />
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.container {
    max-width: 900px;
    margin: 0 auto;
}

.table th, .table td {
    text-align: center;
}

button {
    padding: 6px 12px;
}
</style>
