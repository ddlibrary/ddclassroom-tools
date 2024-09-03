<template>

    <Head title="Create Student" />
    <AuthenticatedLayout>
        <div class="py-4 bg-sky-30 rounded-lg border">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Create Student -->
                    <form @submit.prevent="submit">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">
                                    Create Score
                                </h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">
                                    You can add student's midterm score based on her final
                                </p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <!-- Moodle ID -->
                                    <div class="sm:col-span-1">
                                        <label for="moodle_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Moodle ID
                                        </label>
                                        <div class="mt-2">
                                            <input v-model="form.moodle_id" name="moodle" id="moodle"
                                                autocomplete="moodle"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            <p class="mt-2 text-sm text-red-500" v-if="errors.moodle_id">
                                                {{ errors . moodle_id }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Grade -->
                                    <div class="sm:col-span-1">
                                        <label for="sub_grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Grade
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.sub_grade_id" name="grade" id="grade"
                                                autocomplete="grade"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="grade in grades" :value="grade.id"
                                                    :key="grade">
                                                    {{ grade . full_name }}
                                                </option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.sub_grade_id">
                                                {{ errors . sub_grade_id }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label for="subject_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Subject
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.subject_id" name="subject" id="subject"
                                                autocomplete="subject"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="subject in subjects" :value="subject.id"
                                                    :key="subject">
                                                    {{ subject . name }}
                                                </option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.subject_id">
                                                {{ errors . subject_id }}
                                            </p>
                                        </div>
                                    </div>



                                    <!-- Year -->
                                    <div class="sm:col-span-1">
                                        <label for="year"
                                            class="block text-sm font-medium leading-6 text-gray-900">Year
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.year" name="year" id="year"
                                                autocomplete="year"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="year in years" :value="year.name"
                                                    :key="year">
                                                    {{ year . name }}
                                                </option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.year">
                                                {{ errors . year }}
                                            </p>
                                        </div>
                                    </div>




                                    <div class="sm:col-span-1 content-end">
                                        <label for="teacher" class="block text-sm font-medium leading-6 text-gray-900">
                                        </label>
                                        <div class="mt-2">
                                            <button type="submit" :disabled="form.year && form.moodle_id && form.sub_grade_id ? false : true"
                                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                Change Score
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
    import Swal from "sweetalert2";
    import {
        reactive
    } from "vue";
    import {
        Head
    } from "@inertiajs/vue3";
    import {
        router
    } from "@inertiajs/vue3";

    const props = defineProps([
        "grades",
        "errors",
        "years",
        "subjects",
    ]);

    const form = reactive({
        sub_grade_id: "",
        year: "",
        subject_id: "",
        moodle_id: "",
    });

    function submit() {
        if (confirm("Are you sure to add this subject's scores?")) {
            router.post(route(`add-midterm-score-based-on-final`),
                form, {
                    forceFormData: true,
                    onFinish: (res) => {
                        Swal.fire(`Created`, `Student's scores have been successfully changed.`);
                    },
                });
        }
    }
</script>
