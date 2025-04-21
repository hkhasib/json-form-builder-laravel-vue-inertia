<template>
    <DashboardLayout>
        <h2>Upload JSON</h2>
        <p>Edit or upload a JSON file. The file is optional, but the editor must have content.</p>

        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <form @submit.prevent="handleNext">
            <div class="mb-3">
                <label for="jsonFile" class="form-label">Upload JSON File (Optional)</label>
                <input type="file" id="jsonFile" class="form-control" @change="handleFileUpload" />
            </div>

            <div class="mb-3">
                <label for="jsonEditor" class="form-label">JSON Editor</label>
                <div id="jsonEditor" style="height: 500px; border: 1px solid #ccc;"></div>
            </div>

            <div class="mb-3">
                <p class="text-danger" v-if="formError">JSON content is required.</p>
            </div>

            <div v-if="validationErrors.length" class="alert alert-danger">
                <ul>
                    <li v-for="(error, index) in validationErrors" :key="index">{{ error }}</li>
                </ul>
            </div>

            <div class="text-center" style="z-index: 1000;">
                <button type="submit" class="btn btn-primary w-25">Next</button>
            </div>
        </form>

        // the form preview field is initially hidden
        <div v-if="isFormVisible">
            <h2>{{ form.title }}</h2>

            <form :action="form.action" :method="form.method">
                <div v-for="field in form.fields" :key="field.name" class="form-group">
                    <label :for="field.name">{{ field.label }}</label>

                    <input
                        v-if="field.type === 'text' || field.type === 'email'"
                        :type="field.type"
                        :name="field.name"
                        :placeholder="field.placeholder"
                        :required="field.required"
                        v-model="formData[field.name]"
                        class="form-control"
                    />

                    <textarea
                        v-if="field.type === 'textarea'"
                        :name="field.name"
                        :placeholder="field.placeholder"
                        :required="field.required"
                        v-model="formData[field.name]"
                        class="form-control"
                    ></textarea>

                    <select
                        v-if="field.type === 'select'"
                        :name="field.name"
                        :required="field.required"
                        v-model="formData[field.name]"
                        class="form-control"
                    >
                        <option disabled value="">Please select</option>
                        <option v-for="option in field.options" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-3" disabled>Submit Form</button>
            </form>

            <hr>

            <button type="button" class="btn btn-primary w-100 mt-4" @click="submitToDatabase">
                Continue to the Editor
            </button>
        </div>
    </DashboardLayout>
</template>

<script lang="ts">
import JSONEditor from 'jsoneditor';
import 'jsoneditor/dist/jsoneditor.css';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { router, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

export default {
    components: { DashboardLayout },
    data() {
        return {
            editor: null,
            formError: false,
            isFormVisible: false,
            form: {
                title: '',
                method: '',
                action: '',
                fields: [],
            },
            formData: {},
            validationErrors: [],
        };
    },
    props: {
        errors: Object,
    },
    mounted() {
        const container = document.getElementById('jsonEditor');
        this.editor = new JSONEditor(container, {
            mode: 'code',
            onChange: () => {
                this.formError = false;
            },
        });

        if (this.successMessage) {
            setTimeout(() => {
                this.$page.props.flash.success = null;
            }, 5000);
        }
    },
    computed: {
        successMessage() {
            return this.$page.props.flash?.success || '';
        }
    },
    methods: {
        // Handle file upload
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    try {
                        // Parse the JSON content and set it in the editor
                        const jsonContent = JSON.parse(reader.result);
                        this.editor.set(jsonContent);
                        this.formError = false;
                    } catch {
                        this.formError = true;
                    }
                };
                reader.readAsText(file);
            }
        },
        handleNext() {
            try {
                const jsonContent = this.editor.get();
                if (!jsonContent) throw new Error('Empty');

                const errors = this.validateJson(jsonContent);
                if (errors.length > 0) {
                    this.validationErrors = errors;
                    this.formError = false;
                    return;
                }

                jsonContent.fields.forEach((field, index) => {
                    field.position = index;
                });

                this.form = jsonContent;
                this.isFormVisible = true;
                this.initializeFormData();
                this.formError = false;
                this.validationErrors = [];
            } catch {
                this.formError = true;
                this.validationErrors = [];
            }
        },
        // Initialize form data based on the fields
        initializeFormData() {
            this.formData = {};
            this.form.fields.forEach((field) => {
                this.formData[field.name] = '';
            });
        },
        validateJson(jsonContent) {
            const errors = [];

            if (!jsonContent.title || typeof jsonContent.title !== 'string') {
                errors.push("Missing or invalid 'title'");
            }

            if (!['GET', 'POST', 'PUT', 'DELETE'].includes(jsonContent.method)) {
                errors.push("Invalid or missing 'method'");
            }

            if (typeof jsonContent.action !== 'string') {
                errors.push("Invalid or missing 'action'");
            }

            if (!Array.isArray(jsonContent.fields)) {
                errors.push("'fields' must be an array");
            } else {
                const names = new Set();
                jsonContent.fields.forEach((field, index) => {
                    if (!field.type || typeof field.type !== 'string') {
                        errors.push(`Field ${index + 1} missing or invalid 'type'`);
                    }

                    if (!field.name || typeof field.name !== 'string') {
                        errors.push(`Field ${index + 1} missing or invalid 'name'`);
                    } else if (names.has(field.name)) {
                        errors.push(`Duplicate 'name' found: ${field.name}`);
                    } else {
                        names.add(field.name);
                    }

                    if (!field.label || typeof field.label !== 'string') {
                        errors.push(`Field ${index + 1} missing or invalid 'label'`);
                    }

                    if (field.type === 'select') {
                        if (!Array.isArray(field.options)) {
                            errors.push(`Field '${field.name}' is 'select' but 'options' is missing or not an array`);
                        }
                    }
                });
            }

            return errors;
        },
        showToast(message, type, position = "bottom-right", toastId = null) {
            toast(message, {
                toastId,
                type,
                position,
                pauseOnHover: false,
                pauseOnFocusLoss: false,
                autoClose: 3000,
                hideProgressBar: true,
                dangerouslyHTMLString: true,
            });
        },
        submitToDatabase() {
            if (!this.form || !this.form.fields || this.form.fields.length === 0) {
                this.showToast('Please fill out the form before saving.', 'error');
                return;
            }

            const payload = {
                title: this.form.title,
                method: this.form.method,
                action: this.form.action,
                fields: this.form.fields,
            };

            router.post('/form/draft', payload, {
                preserveScroll: true,
                onSuccess: () => {
                    this.showToast('Successfully draft!', 'success');
                },
                onError: (errors) => {
                    this.validationErrors = Object.values(errors).flat();
                },
            });
        },
    },
};
</script>

<style scoped>
form {
    max-width: 800px;
    margin: 0 auto;
}

button {
    margin-top: 20px;
}

.text-danger {
    margin-top: 10px;
}
</style>
