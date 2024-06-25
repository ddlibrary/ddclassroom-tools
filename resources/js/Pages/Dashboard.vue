<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    import {
        Head
    } from '@inertiajs/vue3';

    defineProps(['subGrades', 'generalView']);
</script>

<template>

    <Head title="Darakht-e-Danesh" />

    <AuthenticatedLayout>
        <div class="bg-gray-100 p-4">
            <h3 class="text-base font-semibold leading-6 text-gray-900">2024</h3>
            <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div v-if="generalView"
                    class="overflow-hidden rounded-lg bg-blue-200 px-4 py-5 shadow sm:p-6">
                    <dt class="truncate pb-4 text-sm font-medium text-gray-500">All Grades</dt>

                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-green-700">
                            Total Students
                            <span class="ml-2 text-sm font-medium text-gray-500">100%</span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">

                            {{  generalView['total_students'] }}
                        </div>
                    </dd>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-green-700">
                            Passed
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ generalView['total_students'] > 0 ? ((generalView['total_passed'] * 100) / generalView['total_students']).toFixed(2) : 0 }}%
                                </span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{  generalView['total_passed'] }}
                        </div>
                    </dd>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-red-400">
                            Conditional
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ generalView['total_students'] > 0 ? ((generalView['total_conditional'] * 100) / generalView['total_students']).toFixed(2) : 0 }}%
                            </span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-600 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-400" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{  generalView['total_conditional'] }}
                        </div>
                    </dd>

                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-red-800">
                            Failed
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ generalView['total_students'] > 0 ? ((generalView['total_failed'] * 100) / generalView['total_students']).toFixed(2) : 0 }}%
                            </span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{  generalView['total_failed'] }}
                        </div>
                    </dd>
                </div>
                <div v-for="subGrade in subGrades" :key="subGrade"
                    class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate pb-4 text-sm font-medium text-gray-500">{{ subGrade . name }}</dt>

                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-green-700">
                            Total Students
                            <span class="ml-2 text-sm font-medium text-gray-500">100%</span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">

                            {{  subGrade.total_students }}
                        </div>
                    </dd>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-green-700">
                            Passed
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ subGrade.total_students > 0 ? ((subGrade.passed_count * 100) / subGrade.total_students).toFixed(2) : 0 }}%
                                </span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{  subGrade.passed_count }}
                        </div>
                    </dd>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-red-400">
                            Conditional
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ subGrade.total_students > 0 ? ((subGrade.try_again * 100) / subGrade.total_students).toFixed(2) : 0 }}%
                            </span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-600 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-400" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{  subGrade.try_again }}
                        </div>
                    </dd>

                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline  font-semibold text-red-800">
                            Failed
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ subGrade.total_students > 0 ? ((subGrade.failed * 100) / subGrade.total_students).toFixed(2) : 0 }}%
                            </span>
                        </div>

                        <div
                            class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{  subGrade.failed }}
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </AuthenticatedLayout>
</template>
