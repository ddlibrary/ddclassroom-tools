<template>

    <Head title="Year List" />

    <AuthenticatedLayout>
        <!-- year List -->
        <div class="align-middle min-w-full p-2 bg-gray-50 overflow-x-auto shadow overflow-hidden sm:rounded-lg">
            <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
                <div class="grid grid-cols-1 gap-4 lg:col-span-2 ml-2">

                    <!-- Searching -->
                    <div class="flow-root">
                        <div class="space-y-9 divide-y divide-gray-200">
                            <div
                                class="mt-6  grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">
                                <div class="sm:col-span-4">
                                    <div class="mt-1 rounded-md shadow-sm flex">
                                        <input name="name" v-model="yearName" id="username"
                                            autocomplete="username"
                                            placeholder="Search"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- year List -->
                    <div class="flex flex-col mt-2">
                        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="bg-white" v-for="year in years.data" :key="year">
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            <span @click="edit(year)" class="text-gray-900 font-medium"
                                                v-text="year.name"> </span>
                                        </td>
                                        <!-- Edit -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <span @click="edit(year)" class="text-indigo-600 hover:text-indigo-900">
                                                Edit </span>
                                            <span class="text-red-700 cursor-pointer ml-2"
                                                @click="deleteItem(year.id)">
                                                Delete
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <no-record-found v-if="years.data.length == 0"></no-record-found>
                        <!-- Pagination -->
                        <div v-if="years.links.length > 3" class="mt-2">
                            <div class="flex flex-wrap -mb-1">
                                <template v-for="(link, p) in years.links" :key="p">
                                    <div v-if="link.url === null"
                                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                        v-html="link.label" />
                                    <Link v-else
                                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                                        :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url"
                                        v-html="link.label" />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 mr-2 mt-2">

                    <!-- Create new year -->
                    <section aria-labelledby="announcements-title">
                        <div class="rounded-lg bg-white overflow-hidden shadow">
                            <div class="p-6">
                                <div class="flow-roots">
                                    <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="submit">
                                        <div class="space-y-8 divide-y divide-gray-200">
                                            <div>
                                                <div>
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                        <span v-if="form.id">
                                                            Update year
                                                        </span>
                                                        <span v-else>
                                                            Create new year
                                                        </span>
                                                    </h3>
                                                </div>

                                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                                                    <!-- Name -->
                                                    <div class="sm:col-span-6">
                                                        <label for="username"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Name
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <input type="text" placeholder="Name" v-model="form.name"
                                                                name="username" id="username" autocomplete="username"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.name">
                                                            {{ errors . name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit button -->
                                        <div class="pt-5">
                                            <div class="flex justify-end">
                                                <button type="button" @click="resetForm()"
                                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Cancel
                                                </button>

                                                <button v-if="form.id == null" type="submit" :disabled="isEnabled"
                                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    :class="isEnabled ? 'bg-indigo-200 hover:bg-indigo-200' :
                                                        'bg-indigo-600 hover:bg-indigo-700'">
                                                    Save
                                                </button>
                                                <button v-else type="submit" :disabled="isEnabled"
                                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    :class="isEnabled ? 'bg-indigo-200 hover:bg-indigo-200' :
                                                        'bg-indigo-600 hover:bg-indigo-700'">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NoRecordFound from "./../Partials/NoRecordFound.vue";
    import Pagination from "@/Components/Pagination.vue";
    import Swal from 'sweetalert2'
    import {
        Inertia
    } from '@inertiajs/inertia';
    import {
        computed,
        defineComponent,
        reactive,
        ref
    } from 'vue';
    import {
        Switch
    } from '@headlessui/vue';
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import {
        router
    } from '@inertiajs/vue3';

    import debounce from 'lodash.debounce'
    import {
        watch
    } from 'vue';

    const props = defineProps(['years', 'errors']);

    const enabled = ref(false);
    const isEnabled = ref(false);

    const form = reactive({
        name: null,
        id: null,
    });

    function deleteItem(id) {
        if (confirm('Are you sure to delete this year?')) {
            router.delete(route(`years.destroy`, {
                'id': id
            }), {
                forceFormData: true,
                onFinish: () => {
                    Swal.fire(`Deleted`,
                        `Year has been successfully deleted.`)
                },
            });
        }
    }

    let yearName = ref(null);

    watch(
        yearName,
        debounce(function(value) {
            router.get(route('years.index'), {
                name: value
            }, {
                preserveState: true,
            });
        }, 500),
    );


    // Submit the form
    function submit() {
        router.post(route('years.store'), form, {
            forceFormData: true,
            onStart: () => (isEnabled.value = true),
            onFinish: () => {
                isEnabled.value = false;
                let error = props.errors;
                if (!error.name) {
                    resetForm();
                    this.getSuccessMessage(`New year successfully ${form.id ? 'updated.' : 'added.'}`);
                    Swal.fire(`Wow`,
                        `Year successfully ${form.id ? 'updated.' : 'added.'}`)
                }
            },
        });
    }

    // Reset the form
    function resetForm() {
        form.id = null;
        form.name = null;
    }

    // Edit 
    function edit(year) {
        form.id = year.id
        form.name = year.name;
    }
</script>
