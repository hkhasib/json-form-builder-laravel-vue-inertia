<template>
    <DashboardLayout>
        <div class="flex">
            <!-- Main Form Editor -->
            <div>
                <div class="text-center">
                    <h2>{{ updatedForm.title }}</h2>
                </div>

                <form :action="updatedForm.action" :method="updatedForm.method" @submit.prevent>
                    <draggable
                        v-model="updatedForm.fields"
                        group="fields"
                        item-key="name"
                        handle=".drag-handle"
                        @end="onDragEnd"
                    >
                        <template #item="{ element }">
                            <div
                                class="form-group"
                                :key="element.name"
                                @click="selectField(element)"
                                :class="{ 'selected': selectedField?.name === element.name }"
                            >
                                <div class="drag-handle">≡</div>
                                <label :for="element.name">{{ element.label }}</label>

                                <input
                                    v-if="element.type === 'text' || element.type === 'email'"
                                    :type="element.type"
                                    :name="element.name"
                                    :placeholder="element.placeholder"
                                    :required="element.required"
                                    :value="formData[element.name]"
                                    :class="element.class_name || 'form-control'"
                                    :style="element.custom_style"
                                />

                                <textarea
                                    v-if="element.type === 'textarea'"
                                    :name="element.name"
                                    :placeholder="element.placeholder"
                                    :required="element.required"
                                    :value="formData[element.name]"
                                    :class="element.class_name || 'form-control'"
                                    :style="element.custom_style"
                                ></textarea>

                                <select
                                    v-if="element.type === 'select'"
                                    :name="element.name"
                                    :required="element.required"
                                    :value="formData[element.name]"
                                    :class="element.class_name || 'form-control'"
                                    :style="element.custom_style"
                                >
                                    <option value="" disabled>Select an option</option>
                                    <option
                                        v-for="option in element.options"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                        </template>
                    </draggable>
                    <div class="text-center">
                        <button class="btn btn-primary" disabled>
                            Submit
                        </button>
                    </div>
                </form>
                <br><br>
                <hr>
                <!-- Status Dropdown -->
                <div class="form-group">
                    <label for="status">Form Status</label>
                    <select v-model="updatedForm.status" class="form-control" id="status" name="status">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <!-- Save Button -->
                <div class="text-center mt-4" style="z-index: 1000;">
                    <button class="btn btn-outline-success w-25" @click="saveEntireForm">
                        💾 Update Entire Form
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar for Field Settings -->
        <div v-if="selectedField" class="fixed-sidebar p-3">
            <EditFieldSettings :field="selectedField" @save-settings="saveFieldSettings" />
            <button class="btn btn-sm btn-danger mt-2 w-100" @click="selectedField = null">Close</button>
        </div>
    </DashboardLayout>
</template>

<script lang="ts">
import { router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import draggable from 'vuedraggable';
import EditFieldSettings from "@/Components/EditFieldSettings.vue";
import swal from 'sweetalert';

export default {
    components: {
        EditFieldSettings,
        DashboardLayout,
        draggable,
    },
    props: {
        id: Number,
        form: Object,
    },
    data() {
        return {
            updatedForm: {
                id: this.id,
                title: this.form.title,
                action: this.form.action,
                method: this.form.method,
                fields: [...this.form.fields].sort((a, b) => a.position - b.position),
                status: this.form.status || 'draft',
            },
            formData: {},
            selectedField: null,
        };
    },
    mounted() {
        this.fetchForm();
    },
    methods: {
        fetchForm() {
            this.updatedForm.fields.forEach((field) => {
                this.formData[field.name] = '';
            });
        },
        onDragEnd() {
            this.updatePositions();
            this.savePositions();
        },
        updatePositions() {
            this.updatedForm.fields.forEach((field, index) => {
                field.position = index;
            });
        },
        savePositions() {
            // router.post('/form/update-positions', {
            //     id: this.updatedForm.id,
            //     fields: this.updatedForm.fields.map(f => ({
            //         id: f.id,
            //         position: f.position,
            //         class_name: f.class_name,
            //         custom_style: f.custom_style,
            //     })),
            // }, {
            //     preserveScroll: true,
            //     onSuccess: () => {
            //         console.log('Positions updated');
            //     },
            // });
        },
        selectField(field) {
            this.selectedField = field;
        },
        saveFieldSettings(updatedField) {
            const index = this.updatedForm.fields.findIndex(f => f.name === updatedField.name);
            if (index !== -1) {
                this.updatedForm.fields[index] = updatedField;
            }
            this.selectedField = null;
        },
        saveEntireForm() {
            this.updatePositions();
            router.put(`/form/${this.id}`, {
                id: this.updatedForm.id,
                title: this.updatedForm.title,
                action: this.updatedForm.action,
                method: this.updatedForm.method,
                status: this.updatedForm.status,
                fields: this.updatedForm.fields.map(f => ({
                    id: f.id,
                    name: f.name,
                    type: f.type,
                    label: f.label,
                    placeholder: f.placeholder,
                    required: f.required,
                    position: f.position,
                    class_name: f.class_name,
                    custom_style: f.custom_style,
                    options: f.options || [],
                })),
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    swal("💖 Success", "Form saved successfully!!", "success");
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

.form-group {
    border: 1px dashed #ccc;
    padding: 12px;
    margin-bottom: 10px;
    background: #fafafa;
    border-radius: 8px;
    cursor: grab;
    position: relative;
}

.drag-handle {
    position: absolute;
    top: 8px;
    right: 10px;
    cursor: grab;
    font-weight: bold;
    font-size: 18px;
    color: #888;
}

.form-group.selected {
    border-color: #007bff;
    background: #eaf4ff;
}

textarea.form-control,
input.form-control,
select.form-control {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-top: 4px;
}

.fixed-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 300px;
    background-color: #fff;
    border-left: 2px solid #ccc;
    height: 100%;
    z-index: 999;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .fixed-sidebar {
        width: 100%;
        top: auto;
        bottom: 0;
        height: 50%;
        border-left: none;
        border-top: 2px solid #ccc;
    }
}
</style>
