<template>
    <div class="container my-5">
        <h2 class="text-center mb-4">{{ form.title }}</h2>

        <form :action="form.action" :method="form.method" @submit.prevent="submitForm">
            <div v-for="field in sortedFields" :key="field.name" class="mb-3">
                <label :for="field.name" class="form-label">{{ field.label }}</label>

                <input
                    v-if="field.type === 'text' || field.type === 'email'"
                    :type="field.type"
                    class="form-control"
                    :name="field.name"
                    :placeholder="field.placeholder"
                    :required="field.required"
                    v-model="formData[field.name]"
                    :class="field.class_name"
                    :style="field.custom_style"
                />

                <textarea
                    v-else-if="field.type === 'textarea'"
                    class="form-control"
                    :name="field.name"
                    :placeholder="field.placeholder"
                    :required="field.required"
                    v-model="formData[field.name]"
                    :class="field.class_name"
                    :style="field.custom_style"
                ></textarea>

                <select
                    v-else-if="field.type === 'select'"
                    class="form-select"
                    :name="field.name"
                    :required="field.required"
                    v-model="formData[field.name]"
                    :class="field.class_name"
                    :style="field.custom_style"
                >
                    <option value="" disabled>Select an option</option>
                    <option v-for="option in field.options" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script lang="ts">
export default {
    props: {
        form: Object,
    },
    data() {
        return {
            formData: {},
        };
    },
    computed: {
        sortedFields() {
            return [...this.form.fields].sort((a, b) => a.position - b.position);
        }
    },
    mounted() {
        this.sortedFields.forEach(field => {
            this.formData[field.name] = '';
        });
    },
    methods: {
        submitForm() {
            console.log('Form submitted with:', this.formData);
            alert('Form submitted successfully! ❤️');
        }
    }
};
</script>

<style scoped>
body {
    background-color: #f8f9fa;
}
</style>
