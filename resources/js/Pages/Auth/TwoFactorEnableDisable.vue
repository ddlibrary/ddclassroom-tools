<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {
        Head
    } from '@inertiajs/vue3';
    import Swal from "sweetalert2";
    import {
        router
    } from "@inertiajs/vue3";
    import { ref } from 'vue';

    const props = defineProps(['qrCode', 'recoveryCodes']);

    const isDisabled = ref(false);

    function enable2fa() {
        isDisabled.value = true;
        router.get(route('enable-2fa'), {}, {
            forceFormData: true,
            onFinish: (res) => {
                Swal.fire(`Enabled`, `Your two factor authentication successully enabled.`);
                isDisabled.value = false;
            },
        });
    }

    function disable2fa() {
        if(confirm('Are you to disable your 2fa?')){
            isDisabled.value = true;
            router.post(route('disable-2fa'), {}, {
                forceFormData: true,
                onFinish: (res) => {
                    Swal.fire(`Disabled`, `Your two factor authentication successully disabled.`);
                    isDisabled.value = false;
                },
            });
        }
    }

    function copyBackupCodes() {
        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = props.recoveryCodes.join('\n');

        document.body.appendChild(tempTextArea);

        tempTextArea.select();

        document.execCommand('copy');

        document.body.removeChild(tempTextArea);

        alert('Backup codes copied!');
    }
</script>

<template>

    <Head title="Darakht-e-Danesh" />

    <AuthenticatedLayout>
        <div class="flex justify-center content-center m-16">

            <div class="bg-gray-100 p-4" v-if="qrCode">
                <div class="pb-8 inline-flex w-full justify-center gap-x-1.5 ">
                    <div>
                        <p class="pb-4">Scan this QR code with your authenticator app to enable two-factor authentication.</p>
                        <div v-html="qrCode"></div>
                    </div>
                    <div class="px-4">
                        <div class="flex justify-between mb-8">

                            <h3 class="xl-text">Backup Codes</h3>
                            <button @click="copyBackupCodes"
                                class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Copy</button>
                        </div>
                        <span class="bg-green-300 p-2 mb-2 mr-2 inline-block rounded"
                            v-for="code in recoveryCodes">{{ code }}</span>
                    </div>
                </div>
                <button :disabled="isDisabled" type="button" @click="disable2fa()"
                    class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Disable Two Factor Authentication
                </button>
            </div>
            <div class="p-4" v-else>
                <h1 class="text-2xl text-center mb-8">Please enable your 2fa</h1>
                <button :disabled="isDisabled" type="button" @click="enable2fa()"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Enable Two Factor Authentication
                </button>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
