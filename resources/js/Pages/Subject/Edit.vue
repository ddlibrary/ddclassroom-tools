<template>

    <Head title="Edit subject" />
    <AuthenticatedLayout>

        <!-- subject list btn -->
        <div class="my-2">
            <Link href="/subjects"
                class="pointer-events-auto  mb-2 rounded-md bg-indigo-600 px-3 py-2 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
            subject list
            </Link>
        </div>
        <div class="py-4 bg-sky-30 rounded-lg border">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- subject List -->
                    <form @submit.prevent="submit">
                        <div class="space-y-12">

                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Edit subject</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">You can edit subject info
                                </p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    <!-- Name -->
                                    <div class="sm:col-span-3">
                                        <label for="pname"
                                            class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                                        <div class="mt-2">
                                            <input type="text" v-model="form.name" name="name" id="name"
                                                autocomplete="given-name"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                            <p class="mt-2 text-sm text-red-500" v-if="errors.name">{{ errors . name }}
                                            </p>
                                        </div>
                                    </div>


                                    <!-- Is Active -->
                                    <div class="sm:col-span-6 block">
                                        <label for="postal-code"
                                            class="block text-sm font-medium leading-6 text-gray-900">Is Active
                                        </label>
                                        <div class="mt-2">
                                            <Switch @click="form.is_active = !form.is_active, isActive= true"
                                                :class="[form.is_active ? 'bg-indigo-600' : 'bg-gray-200',
                                                    'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                                ]">
                                                <span class="sr-only">Is Active</span>
                                                <span
                                                    :class="[form.is_active ? 'translate-x-5' : 'translate-x-0',
                                                        'pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
                                                    ]">
                                                    <span
                                                        :class="[form.is_active ? 'opacity-0 ease-out duration-100' :
                                                            'opacity-100 ease-in duration-200',
                                                            'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity'
                                                        ]"
                                                        aria-hidden="true">
                                                        <svg class="h-3 w-3 text-gray-400" fill="none"
                                                            viewBox="0 0 12 12">
                                                            <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                    <span
                                                        :class="[form.is_active ? 'opacity-100 ease-in duration-200' :
                                                            'opacity-0 ease-out duration-100',
                                                            'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity'
                                                        ]"
                                                        aria-hidden="true">
                                                        <svg class="h-3 w-3 text-indigo-600" fill="currentColor"
                                                            viewBox="0 0 12 12">
                                                            <path
                                                                d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            </Switch>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <Link href="/subjects">
                            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                            </Link>

                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ErrorsAndMessages from "../Partials/ErrorsAndMessages.vue";
    import NoRecordFound from "../Partials/NoRecordFound.vue";
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
    import { router } from '@inertiajs/vue3';

    const props = defineProps(['subject', 'errors']);

    const form = reactive({
        id: props.subject.id,
        name: props.subject.name,
        is_active: props.subject.is_active,
        _method: 'put'
    })

    function submit() {
        router.post(route(`subjects.update`, {
            'id': form.id
        }), form)
    }
</script>
