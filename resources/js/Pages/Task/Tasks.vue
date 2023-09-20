<script setup>
    //import * as XLSX from 'xlsx';
    //import * as XLSX from 'xlsx-style';
    import ExcelJS from 'exceljs';
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import {
        onMounted
    } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TaskMedia from "@/Components/TaskMedia.vue"

    import useTasks from "@/Composables/useTasks"

    const {
        tasks,
        getTasks
    } = useTasks()

    onMounted(getTasks)

    // Function to download the page
// Function to download the entire page
    const downloadExcel = async () => {
    const fileName = "tasks.xlsx"; // Change the filename as desired

    // Fetch tasks data from the backend
    try { // Change the filename as desired
       const response = await fetch('/api/tasks'); // Replace with your actual API endpoint
        const tasksData = await response.json();

        // Create a new workbook
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Tasks');

        // Add the tasks data to the worksheet
        worksheet.columns = [
            { header: 'name', key: 'name', width: 35 },
            { header: 'description', key: 'description', width: 35 },
            { header: 'due_date', key: 'due_date', width: 35 },
            { header: 'department', key: 'department', width: 35 },
            { header: 'status', key: 'status', width: 35 },
        ];

        tasksData.forEach(task => {
            worksheet.addRow(task);
        });

        // Create a blob from the workbook
        const buffer = await workbook.xlsx.writeBuffer();
        const excelBlob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

        // Create a download link
        const url = window.URL.createObjectURL(excelBlob);

        const a = document.createElement("a");
        a.href = url;
        a.download = fileName;

        // Trigger the download
        a.click();
    } catch (error) {
        console.log(error);
    }

};
</script>

<template>

    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Patients</h2>
        </template>

        <div
             class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            <div class="max-w-7xl mx-auto p-6 lg:p-8">

                <Link href="/tasks/create">
                <PrimaryButton class="ml-4 hover:opacity-25">Check in patient</PrimaryButton>
                </Link>
                <button @click="downloadExcel" class="ml-4 hover:opacity-25">Download Excel</button>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <Link v-for="(task, key) in tasks"
                              :href="`/tasks/${task.id}`">
                        <TaskMedia :key="key"
                                   :task="task" />
                        </Link>
                    </div>
                </div>

                <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                        <div class="flex items-center gap-4">
                            <a href="https://www.black.co.ke"
                               class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     class="-mt-px mr-1 w-5 h-5 stroke-gray-400 dark:stroke-gray-600 group-hover:stroke-gray-600 dark:group-hover:stroke-gray-400">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                Sponsor
                            </a>
                        </div>
                    </div>

                    <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                        Solutech Code Challenge
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
