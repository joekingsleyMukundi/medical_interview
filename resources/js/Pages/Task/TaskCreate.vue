<script setup>
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import useTasks from '@/Composables/useTasks';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {
        Head,
    } from '@inertiajs/vue3';
    import {
        onMounted,
        ref
    } from 'vue';

    const {
        messages,
        onCreateTask,
    } = useTasks()

    const name = ref("")
    const description = ref("")
    const dueDate = ref("")

    /*
     * Prevent past date in date picker */
    const minDate = () => {
        var today = new Date().toISOString().split('T')[0];
        var myDate = document.getElementById("dueDate");
        myDate.setAttribute('min', today);
    }

    onMounted(() => {
        minDate()
    })
     const department = ref("Nursing");
     const category = ref("Check-in");

</script>

<template>

    <Head title="Edit Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Check in patient</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>

                        <div v-for="(message, key) in messages"
                             :key="key"
                             class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50
                         via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl
                         shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all
                         duration-250 focus:outline focus:outline-2 focus:outline-red-500 dark:text-white justify-center mb-1">
                            <h6>{{ message }}</h6>
                        </div>

                        <form @submit.prevent="() => onCreateTask(name, description, dueDate, department, category)"
                              class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="name"
                                            value="Name" />

                                <input id="name"
                                       type="text"
                                       placeholder="Name"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                           focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                       v-model="name"
                                       required
                                       autofocus
                                       autocomplete="name" />
                            </div>

                            <div>
                                <InputLabel for="description"
                                            value="Issue" />

                                <textarea id="name"
                                          type="text"
                                          placeholder="Describe the reason for hospital visit"
                                          class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                           focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                          v-model="description"
                                          rows="10"
                                          required
                                          autofocus
                                          autocomplete="description">
										</textarea>
                            </div>

                            <div>
                                <InputLabel for="department" value="Department" />

                                <select
                                    id="department"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                    focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="department"
                                    required
                                >
                                    <option value="Nursing">Nursing</option>
                                    <option value="Laboratory">Laboratory</option>
                                    <option value="Radiology">Radiology</option>
                                    <option value="Treatment">Treatment</option>
                                    <option value="Optical">Optical</option>
                                    <option value="Reception">Reception</option>
                                </select>
                            </div>

                            <div>
                                <InputLabel for="category" value="Category" />

                                <select
                                    id="category"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                    focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="category"
                                    required
                                >
                                    <option value="Check-in">Check-in</option>
                                    <option value="Treatment">Treatment</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Check-out">Check-out</option>
                                </select>
                            </div>

                            <div>
                                <InputLabel for="dueDate"
                                            value="date" />

                                <input id="dueDate"
                                       type="date"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                           focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                       v-model="dueDate"
                                       required
                                       autofocus
                                       autocomplete="description" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton>Save</PrimaryButton>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
