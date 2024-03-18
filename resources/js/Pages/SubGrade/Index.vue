<template>

    <Head title="Grade List" />

    <AuthenticatedLayout>
        <!-- Grade List -->
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
                                        <input name="name" v-model="subGradeName" id="search" autocomplete="search"
                                            placeholder="Search"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                    </div>
                                </div>
                                <div class="sm:col-span-4">
                                    <div class="mt-1 rounded-md shadow-sm flex">
                                        <select name="name" v-model="yearId" @change="getData()"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-grow block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                            <option value="">...Select Year...</option>
                                            <option v-for="year in years" :value="year.name" :key="year">
                                                {{ year . name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- result List -->
                    <div class="flex flex-col mt-2">
                        <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>

                                        <th
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Year
                                        </th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Level
                                        </th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Location
                                        </th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Is Active
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="bg-white" v-for="subGrade in subGrades.data" :key="subGrade">
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            <span @click="edit(subGrade)" class="text-gray-900 font-medium"
                                                v-text="subGrade.name"> </span>
                                        </td>
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            {{ subGrade . year }}
                                        </td>
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            {{ subGrade . level }}
                                        </td>
                                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                            {{ subGrade . location }}
                                        </td>
                                        <td class=" px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            <Switch
                                                @click="subGrade.is_active = !subGrade.is_active, isActive= true, toggleIsActive(subGrade.id, subGrade.is_active)"
                                                :class="[subGrade.is_active ? 'bg-indigo-600' : 'bg-gray-200',
                                                    'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                                ]">
                                                <span class="sr-only">Is Active</span>
                                                <span
                                                    :class="[subGrade.is_active ? 'translate-x-5' : 'translate-x-0',
                                                        'pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
                                                    ]">
                                                    <span
                                                        :class="[subGrade.is_active ? 'opacity-0 ease-out duration-100' :
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
                                                        :class="[subGrade.is_active ? 'opacity-100 ease-in duration-200' :
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
                                        </td>
                                        <!-- Edit -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <span @click="edit(subGrade)" class="text-indigo-600 hover:text-indigo-900">
                                                Edit </span>
                                            <span class="text-red-700 cursor-pointer ml-2"
                                                @click="deleteItem(subGrade.id)">
                                                Delete
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <no-record-found v-if="subGrades.data.length == 0"></no-record-found>
                        <!-- Pagination -->
                        <div v-if="subGrades.links.length > 3" class="mt-2">
                            <div class="flex flex-wrap -mb-1">
                                <template v-for="(link, p) in subGrades.links" :key="p">
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

                    <!-- Create new subGrade -->
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
                                                            Update grade
                                                        </span>
                                                        <span v-else>
                                                            Create new grade
                                                        </span>
                                                    </h3>
                                                </div>

                                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                                                    <!-- Grade -->
                                                    <div class="sm:col-span-6">
                                                        <label for="grade"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Grade
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <select v-model="form.grade_id" name="grade_id"
                                                                id="grade_id"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                                <option value="">...</option>
                                                                <option v-for="grade in grades" :value="grade.id"
                                                                    :key="grade">{{ grade . name }}</option>
                                                            </select>
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.grade_id">
                                                            {{ errors . grade_id }}
                                                        </p>
                                                    </div>

                                                    <!-- Year -->
                                                    <div class="sm:col-span-6">
                                                        <label for="year"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Year
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <select type="text" placeholder="Name"
                                                                v-model="form.year" name="grade" id="grade"
                                                                autocomplete="grade"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                                <option value="">...</option>
                                                                <option v-for="year in years" :value="year.name"
                                                                    :key="year">{{ year . name }}</option>
                                                            </select>
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.year">
                                                            {{ errors . year }}
                                                        </p>
                                                    </div>

                                                    <!-- Location-->
                                                    <div class="sm:col-span-6">
                                                        <label for="location"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Location
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <input type="text" placeholder="Location"
                                                                v-model="form.location" name="location"
                                                                id="location" autocomplete="location"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.location">
                                                            {{ errors . location }}
                                                        </p>
                                                    </div>

                                                    <!-- Level -->
                                                    <div class="sm:col-span-6">
                                                        <label for="level"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Level
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <input type="text" placeholder="Level"
                                                                v-model="form.level" name="level" id="level"
                                                                autocomplete="level"
                                                                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300">
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.level">
                                                            {{ errors . level }}
                                                        </p>
                                                    </div>

                                                    <!-- Is Active -->
                                                    <div class="sm:col-span-6">
                                                        <label for="is-active"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Is Active
                                                        </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm">
                                                            <Switch
                                                                @click="form.is_active = !form.is_active, isActive= true"
                                                                :class="[form.is_active ? 'bg-indigo-600' : 'bg-gray-200',
                                                                    'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                                                ]">
                                                                <span class="sr-only">Is Active</span>
                                                                <span
                                                                    :class="[form.is_active ? 'translate-x-5' :
                                                                        'translate-x-0',
                                                                        'pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
                                                                    ]">
                                                                    <span
                                                                        :class="[form.is_active ?
                                                                            'opacity-0 ease-out duration-100' :
                                                                            'opacity-100 ease-in duration-200',
                                                                            'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity'
                                                                        ]"
                                                                        aria-hidden="true">
                                                                        <svg class="h-3 w-3 text-gray-400"
                                                                            fill="none" viewBox="0 0 12 12">
                                                                            <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                    </span>
                                                                    <span
                                                                        :class="[form.is_active ?
                                                                            'opacity-100 ease-in duration-200' :
                                                                            'opacity-0 ease-out duration-100',
                                                                            'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity'
                                                                        ]"
                                                                        aria-hidden="true">
                                                                        <svg class="h-3 w-3 text-indigo-600"
                                                                            fill="currentColor" viewBox="0 0 12 12">
                                                                            <path
                                                                                d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                                                        </svg>
                                                                    </span>
                                                                </span>
                                                            </Switch>
                                                        </div>
                                                        <p class="mt-2 text-sm text-red-500" v-if="errors.is_active">
                                                            {{ errors . is_active }}
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
    import ErrorsAndMessages from "./../Partials/ErrorsAndMessages.vue";
    import NoRecordFound from "./../Partials/NoRecordFound.vue";
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

    const props = defineProps(['subGrades', 'grades', 'years', 'errors']);

    const enabled = ref(false);
    const isEnabled = ref(false);

    const form = reactive({
        is_active: true,
        location: null,
        level: null,
        grade_id: '',
        year: '',
        id: null,
    });

    function deleteItem(id) {
        if (confirm('Are you sure to delete this Grade?')) {
            router.delete(route(`sub-grades.destroy`, {
                'id': id
            }), {
                forceFormData: true,
                onFinish: () => {
                    Swal.fire(`Deleted`,
                        `Grade has been successfully deleted.`)
                },
            });
        }
    }

    let subGradeName = ref(null);
    let yearId = ref('');

    watch(
        subGradeName,
        debounce(function(value) {
            getData(value)
        }, 500),
    );

    function getData(name = '') {
        router.get(route('sub-grades.index'), {
            name: (name ? name : subGradeName.value),
            year: yearId.value,
        }, {
            preserveState: true,
        });
    }

    const data = reactive({
        'is_active': true,
        'id': null
    });

    const searchForm = reactive({
        year_id: '',
    });

    function toggleIsActive(id, state) {

        data.is_active = state;
        data.id = id;
        router.post('/toggle-sub-grade-is-active', data, {
            forceFormData: true,
            onFinish: () => {
                data.is_active = true;
                data.id = null;
                Swal.fire(`${state ? 'Wow' : 'Opppps'}`,
                    `Grade successfully ${state ? 'enabled.' : 'disabled.'}`)
            },
        });
    }

    // Submit the form
    function submit() {
        router.post(route('sub-grades.store'), form, {
            forceFormData: true,
            onStart: () => (isEnabled.value = true),
            onFinish: () => {
                isEnabled.value = false;
                let error = props.errors;
                if (!error.grade_id && !error.year && !error.location) {
                    resetForm();
                    Swal.fire(`Wow`,
                        `Grade successfully ${form.id ? 'updated.' : 'added.'}`)
                }
            },
        });
    }

    // Reset the form
    function resetForm() {
        form.id = null;
        form.is_active = true;
        form.grade_id = '';
        form.year = '';
        form.level = null;
        form.location = null;
    }

    // Edit 
    function edit(subGrade) {
        form.id = subGrade.id
        form.grade_id = subGrade.grade_id;
        form.level = subGrade.level;
        form.year = subGrade.year;
        form.location = subGrade.location;
        form.is_active = subGrade.is_active;
    }
</script>
