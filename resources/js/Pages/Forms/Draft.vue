<template>
    <div>
        <!-- Step 1: JSON Input Section -->
        <div v-if="!isFormVisible">
            <h1>Enter JSON Data</h1>
            <textarea v-model="jsonInput" placeholder="Paste your JSON here" rows="10" cols="50"></textarea>
            <button @click="handleNext">Next</button>
        </div>

        <!-- Step 2: Dynamic Form Rendering -->
        <div v-if="isFormVisible">
            <h1>{{ form.title }}</h1>
            <form :action="form.action" :method="form.method" @submit.prevent="handleSubmit">
                <div v-for="field in form.fields" :key="field.name" class="form-group">
                    <label :for="field.name">{{ field.label }}</label>

                    <!-- Text input -->
                    <input v-if="field.type === 'text' || field.type === 'email'"
                           :type="field.type"
                           :name="field.name"
                           :placeholder="field.placeholder"
                           :required="field.required"
                           v-model="formData[field.name]" />

                    <!-- Textarea input -->
                    <textarea v-if="field.type === 'textarea'"
                              :name="field.name"
                              :placeholder="field.placeholder"
                              :required="field.required"
                              v-model="formData[field.name]"></textarea>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // Store the raw JSON input from the user
            jsonInput: '',
            isFormVisible: false, // Controls visibility of the form
            form: {},
            formData: {}
        };
    },
    methods: {
        handleNext() {
            try {
                // Parse the JSON input and set it to the form object
                this.form = JSON.parse(this.jsonInput);
                // Show the form and hide the JSON input section
                this.isFormVisible = true;
                // Initialize formData object based on the fields in the form
                this.initializeFormData();
            } catch (error) {
                alert("Invalid JSON format. Please correct it.");
            }
        },
        handleSubmit() {
            console.log('Form submitted:', this.formData);
            // Here you can handle form submission (e.g., send data to an API)
        },
        initializeFormData() {
            // Initialize formData with empty values for all fields
            this.formData = {};
            this.form.fields.forEach(field => {
                this.formData[field.name] = '';
            });
        }
    }
};
</script>

<style scoped>
.form-group {
    margin-bottom: 15px;
}

textarea {
    width: 100%;
}

button {
    margin-top: 10px;
}
</style>
