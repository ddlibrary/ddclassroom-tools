<template>

    <Head title="Student List" />

    <AuthenticatedLayout>
        <!-- Student List -->
        <div class="mb-2">
            <Link href="/students/create"
                class="pointer-events-auto ml-2 float-right mb-2 rounded-md bg-indigo-600 px-3 py-3 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
            Create
            </Link>
            <Link href="/students/create/multiple"
                class="pointer-events-auto float-right mx-2 mb-2 rounded-md bg-indigo-600 px-3 py-3 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
            Upload Student List
            </Link>
            <Link href="/edit-student-info"
                class="pointer-events-auto float-right mb-2 rounded-md bg-indigo-600 px-3 py-3 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
                Edit student info
            </Link>
            <div class="space-y-9 divide-y divide-gray-200 mb-2">
                <div
                    class="mt-6  grid xs:grid-cols-2 gap-y-8 sm:grid-cols-6 md:grid-cols-6 sm:gap-x-6 lg:grid-cols-10 xl:gap-x-8 mr-2">
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <input name="name" v-model="form.search" id="search" autocomplete="search"
                            placeholder="Search"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-1 rounded-md shadow-sm flex">
                            <select name="name" v-model="form.grade_id"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                <option value="">...</option>
                                <option v-for="grade in grades" :value="grade.id" :key="grade">{{ grade.full_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">

            <!-- student list table -->
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NO
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Father Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Username
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Phone
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grade
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Country
                        </th>

                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Password
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
                    <tr v-for="(student, index) in students.data" :key="student.email">

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ index + 1 }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student . fa_name ? student . fa_name : student . name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student . fa_father_name ? student . fa_father_name : student . father_name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student . username }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student . email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student . phone }}
                        </td>

                        <!-- student thumbnail -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.sub_grade.full_name }}
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.country?.name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.password }}
                        </td>

                        <!-- student state(Enabled or Disabled) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ student.is_active ? 'Active' : 'Disabled' }}
                        </td>

                        <!-- Edit -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a :href="'/student-hand-book/' + student.uuid" target="_blank" class="text-indigo-600 mr-2 hover:text-indigo-900">
                            Handbook</a>
                            <a v-if="student.id_number"
                            :href="'https://classroom.darakhtdanesh.org/user/editadvanced.php?id=' + student.id_number"
                            target="_blank" class="text-indigo-600 mr-2 hover:text-indigo-900">
                                Moodle Account</a>

                            <Link :href="'/students/' + student.id + '/edit'" class="text-indigo-600 ml-2 hover:text-indigo-900">
                            Edit</Link>

                            <span class="cursor-pointer ml-2"
                                :class="{ 'text-red-700': student.is_active, 'text-green-700': !student.is_active }"
                                @click="deleteItem(student.id, student.is_active)">
                            Make {{ student.is_active ? 'Disable' : 'Enable' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <no-record-found v-if="students.data.length == 0"></no-record-found>
        <!-- Pagination -->
        <div v-if="students.links.length > 3" class="mt-2">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, p) in students.links" :key="p">
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

    defineProps(['students', 'errors', 'grades']);

    function submit(id) {
        if (confirm('Are you sure to delete this student?')) {
            router.delete(route(`students.destroy`, {
                'id': id
            }));
        }
    }

    const form = reactive({
        search : null,
        country_id : '',
        grade_id : ''
    });

    watch(form, debounce(() => {
        router.get(route('students.index'), form, {
            preserveState: true,
        });
    }, 300));

    function deleteItem(id, is_active) {
        let isActive = is_active ? 'disable' : 'enable';
        if (confirm(`Are you sure to ${isActive} this student?`)) {
            router.delete(route(`students.destroy`, {
                'id': id
            }), {
                forceFormData: true,
                onFinish: () => {
                    Swal.fire(`Deleted`,
                        `Student has been successfully ${is_active ? 'disabled' : 'enabled'}.`)
                },
            });
        }
    }
</script>
