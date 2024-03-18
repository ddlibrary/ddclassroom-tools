<template>

    <Head title="subject List" />

    <AuthenticatedLayout>
        <!-- subject List -->
        <div class="mb-2">
            <Link href="/subjects/create"
                class="pointer-events-auto float-right mb-2 rounded-md bg-indigo-600 px-3 py-2 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
            Create Subject
            </Link>
            <div class="space-y-9 divide-y divide-gray-200">
                <div
                    class="mt-6  grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">
                    <div class="sm:col-span-4">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <input name="name" v-model="resultName" id="search" autocomplete="search"
                            placeholder="Search"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">

            <!-- subject list table -->
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>

                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>

                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="subject in subjects.data" :key="subject.email">

                        <!-- subject thumbnail -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ subject . name }}
                            </div>
                        </td>

                        <!-- subject state(Enabled or Disabled) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ subject . is_active ? 'Active' : 'Disabled' }}
                        </td>

                        <!-- Edit -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link :href="'/subjects/' + subject.id + '/edit'"
                                class="text-indigo-600 hover:text-indigo-900">
                            Edit</Link>
                            <span class="text-red-700 cursor-pointer ml-2" @click="submit(subject.id)">
                                Delete
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>


        </div>
        <no-record-found v-if="subjects.data.length == 0"></no-record-found>
        <!-- Pagination -->
        <div v-if="subjects.links.length > 3" class="mt-2">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, p) in subjects.links" :key="p">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        v-html="link.label" />
                    <Link v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                        :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ErrorsAndMessages from "./../Partials/ErrorsAndMessages.vue";
    import NoRecordFound from "./../Partials/NoRecordFound.vue";
    import Pagination from "@/Components/Pagination.vue";
    import {
        router
    } from '@inertiajs/vue3';
    import {
        computed,
        defineComponent,
        reactive, ref
    } from 'vue'
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import debounce from 'lodash.debounce'
    import {
        watch
    } from 'vue';

    defineProps(['subjects', 'errors']);

    function submit(id) {
        if (confirm('Are you sure to delete this subject?')) {
            router.delete(route(`subjects.destroy`, {
                'id': id
            }));
        }
    }

    let resultName = ref(null);

    watch(
        resultName,
        debounce(function(value) {
            router.get(route('subjects.index'), {
                name: value
            }, {
                preserveState: true,
            });
        }, 500),
    );
</script>
