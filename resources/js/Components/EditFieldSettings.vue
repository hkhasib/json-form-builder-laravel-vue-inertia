<template>
    <div>
        <!-- Label -->
        <div class="mb-3">
            <label class="form-label">Label</label>
            <input
                type="text"
                v-model="field.label"
                class="form-control"
                placeholder="Enter label"
            />
        </div>

        <!-- Placeholder -->
        <div class="mb-3">
            <label class="form-label">Placeholder</label>
            <input
                type="text"
                v-model="field.placeholder"
                class="form-control"
                placeholder="Enter placeholder"
            />
        </div>

        <!-- Required -->
        <div class="mb-3">
            <label class="form-label">Required</label>
            <input
                type="checkbox"
                v-model="field.required"
                class="form-check-input"
            />
        </div>

        <!-- Class Name -->
        <div class="mb-3">
            <label class="form-label">Class Name</label>
            <input
                type="text"
                v-model="field.class_name"
                class="form-control"
                placeholder="Enter class name"
            />
        </div>

        <!-- Custom Styling -->
        <div class="mb-3">
            <label class="form-label">Custom Styling</label>
            <input
                type="text"
                v-model="field.custom_style"
                class="form-control"
                placeholder="Enter custom styles"
            />
        </div>

        <!-- Save Button -->
        <button class="btn btn-primary" @click="saveSettings">Add to Draft</button>
    </div>
</template>

<script lang="ts">
export default {
    props: {
        field: Object,
    },
    methods: {
        saveSettings() {
            this.$emit("save-settings", this.field);
        },
    },
};
</script>

<style scoped>
.field-settings {
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 8px;
}
</style>
