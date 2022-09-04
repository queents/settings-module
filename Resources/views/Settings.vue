<template>
    <div class="px-6 mx-auto">
        <form action="" @submit.prevent="onSubmit">
            <div class="bg-white dark:bg-gray-800 dark:border-gray-600  rounded-lg border">

                <div class="p-4">
                    <ViltForm
                        v-model="form"
                        :rows="$attrs.rows"
                        :errors="form.errors"
                    />
                </div>
            </div>
            <div class="flex justify-end my-5">
                <div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-4 my-2 font-medium tracking-tight text-white transition-colors border border-transparent rounded-lg shadow focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button dark:focus:ring-offset-0 h-9 focus:ring-white bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action"
                    >
                        {{ trans("global.save") }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import ViltForm from "@/Components/Base/Rows/ViltForm.vue";
import ResourceTableLayout from "@@/Layouts/ResourceTableLayout.vue";

export default defineComponent({
    layout: ResourceTableLayout,
    components: {
        AppLayout,
        JetDialogModal,
        JetSecondaryButton,
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
        ViltForm,
    },
    computed: {
        formRows() {
            let rows = this.$attrs.rows;
            let getRows = {};
            for (let i = 0; i < rows.length; i++) {
                getRows[rows[i].name] = rows[i].default;
            }

            return getRows;
        },
        lang() {
            return this.$page.props.data.trans;
        },
        getMessage() {
            return this.$page.props.data.message;
        },
    },
    data() {
        return {
            errors: {},
            form: {},
        };
    },
    mounted() {
        this.form = this.$inertia.form(this.formRows);
    },
    methods: {
        trans(key) {
            return this.lang[key] ? this.lang[key] : key;
        },
        onSubmit() {
            this.form.submit(
                "post",
                route("admin.settings." + this.$attrs.table + ".store"),
                {
                    onSuccess: () => {
                        if (typeof this.getMessage === "object") {
                            if (this.getMessage.type === "error") {
                                this.$toast.error(this.getMessage.message, {
                                    position: "top",
                                    style: {
                                        background: "rgba(210, 45, 61, .8)",
                                        "border-radius": "0.25rem",
                                    },
                                });
                            } else if (this.getMessage.type === "success") {
                                this.$toast.success(this.getMessage.message, {
                                    position: "top",
                                    style: {
                                        background: "#7e3af2",
                                        "border-radius": "0.25rem",
                                    },
                                });
                            }
                        } else {
                            this.$toast.success(this.getMessage, {
                                position: "top",
                                style: {
                                    background: "#7e3af2",
                                    "border-radius": "0.25rem",
                                },
                            });
                        }
                    },
                }
            );
        },
    },
});
</script>
