<template>

    <Head title="Update Student Info" />
    <AuthenticatedLayout>
        <div class="py-4 bg-sky-30 rounded-lg border">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">

                    <h2 class="text-base font-semibold leading-7 text-gray-900">Edit student info

                        <a
                            href="/assets/samples/edit-student-info.xlsx">
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
                    <!-- Edit Student -->
                    <form @submit.prevent="submit">
                        <div class="space-y-12">

                            <div class="border-b border-gray-900/10 pb-12">

                                <p class="mt-1 text-sm leading-6 text-gray-600">You can edit student info via Excel File.
                                </p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    <div class="sm:col-span-6">
                                        <label for="photo" class="block text-sm font-medium text-gray-700">
                                            File
                                        </label>
                                        <div
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload"
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                        <span>Upload a file</span>
                                                        <input id="file-upload" @change="selectFile" name="file-upload"
                                                            type="file" class="sr-only" />
                                                    </label>
                                                    <p class="pl-1"></p>
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    Excel file up to 5MB
                                                </p>
                                            </div>
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.file">
                                                {{ errors . file }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
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
    import axios from 'axios';
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

    const props = defineProps(['errors']);

    const form = reactive({
        file: null,
    });

    function selectFile($event) {
        form.file = $event.target.files[0];
    }

    function submit() {
        router.post(route(`students.update-student-info`), form, {
            forceFormData: true,
            onFinish: (res) => {

                Swal.fire(`Updated`,
                    `Student(s) info successfully updated.`)
            },
        })
    }
</script>
