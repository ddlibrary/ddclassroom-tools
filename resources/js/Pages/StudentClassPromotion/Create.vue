<template>

    <Head title="Create Student" />
    <AuthenticatedLayout>
        <div class="py-4 bg-sky-30 rounded-lg border">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">

                    <h2 class="text-base font-semibold leading-7 text-gray-900">Promot Student Class

                        <a href="/assets/samples/student-attendance-format.xlsx">
                            <button
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 float-right focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 32 32"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Sample
                            </button>
                        </a>
                    </h2>
                    <!-- Create Student -->
                    <form @submit.prevent="submit">
                        <div class="space-y-12">

                            <div class="border-b border-gray-900/10 pb-12">
                                <p class="mt-1 text-sm leading-6 text-gray-600">You can create attendance via Excel
                                    File.
                                </p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    <!-- Grade -->
                                    <div class="sm:col-span-1">
                                        <label for="grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">Previous Class
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.from_sub_grade_id" name="grade" id="grade"
                                                autocomplete="grade"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="grade in grades" :value="grade.id"
                                                    :key="grade">{{ grade . full_name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.grade_id">
                                                {{ errors . grade_id }}</p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label for="grade_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">New Class
                                        </label>
                                        <div class="mt-2">
                                            <select v-model="form.to_sub_grade_id" name="grade" id="grade"
                                                autocomplete="grade"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">...</option>
                                                <option v-for="grade in grades" :value="grade.id"
                                                    :key="grade">{{ grade . full_name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.grade_id">
                                                {{ errors . grade_id }}</p>
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
                                                    :key="year">{{ year . name }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.year">
                                                {{ errors . year }}</p>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="button"
                                class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Pagination -->
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Swal from 'sweetalert2'
    import {
        Inertia
    } from '@inertiajs/inertia';
    import {
        Switch
    } from '@headlessui/vue';
    import {
        computed,
        defineComponent,
        reactive,
        ref
    } from 'vue'
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import {
        router
    } from '@inertiajs/vue3';

    const props = defineProps(['grades', 'errors', 'years', 'teachers', 'subjects']);

    const form = reactive({
        from_sub_grade_id: '',
        to_sub_grade_id: '',
        year: '',
    });

    function selectFile($event) {
        form.file = $event.target.files[0];
    }

    function submit() {
        router.post(route(`student-class-promotion`), form, {
            forceFormData: true,
            onFinish: (res) => {

                Swal.fire(`Created`,
                    `Students have been successfully created.`)
            },
        })
    }
</script>
